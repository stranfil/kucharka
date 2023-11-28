<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Entity\Recept;
use App\Entity\Nastroj;
use App\Entity\Kategorie;
use App\Entity\Ingredience;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;




#[Route('/api', name: 'api')]
class ReceptyController extends AbstractController
{
    private $manager;

    private $validator;

    private $serializer;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;

        $encoders = [new JsonEncoder()];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizers = [new ObjectNormalizer($classMetadataFactory)];
        $this->serializer = new Serializer($normalizers, $encoders);
    }


    #[Route('/recepty', name: 'create_recept', methods:['post'])]
    public function create(Request $request): JsonResponse
    {
        $input = $request->toArray();
        $recept = $this->serializer->denormalize($input, Recept::class, null, ['groups' => 'group1']);


        if(isset($input['autor'])){
            $autorRepository = $this->manager->getRepository(Autor::class);
            $autor = $autorRepository->findOneBy(['name' => $input['autor']]);
            if(!$autor){
                $autor = new Autor;
                $autor->setName($input['autor']);
                $this->manager->persist($autor);
            }
            $recept->setAutor($autor);
        }


        if(isset($input['kategorie'])){
            $kategorieRepository = $this->manager->getRepository(Kategorie::class);
            $kategorie = $kategorieRepository->findOneBy(['name' => $input['kategorie']]);
            if(!$kategorie){
                $kategorie = new Kategorie;
                $kategorie->setName($input['kategorie']);
                $this->manager->persist($kategorie);
            }
            $recept->setKategorie($kategorie);
        }

        if(isset($input['nastroje'])){
            $nastrojRepository = $this->manager->getRepository(Nastroj::class);
            foreach($input['nastroje'] as $nastroj_name){
                $nastroj = $nastrojRepository->findOneBy(['name' => $nastroj_name]);
                if(!$nastroj){
                    $nastroj = new Nastroj;
                    $nastroj->setName($nastroj_name);
                    $this->manager->persist($nastroj);
                }
                $recept->addNastroje($nastroj);
            }
        }

        if(isset($input['ingredience'])){
            $ingredienceRepository = $this->manager->getRepository(Ingredience::class);
            foreach($input['ingredience'] as $ingredience_name){
                $ingredience = $ingredienceRepository->findOneBy(['name' => $ingredience_name]);
                if(!$ingredience){
                    $ingredience = new Ingredience;
                    $ingredience->setName($ingredience_name);
                    $this->manager->persist($ingredience);
                }
                $recept->addIngredience($ingredience);
            }
        }
        

        $errors = $this->validator->validate($recept);
        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->json([
                $errorsString
            ]);
        }

        $this->manager->persist($recept);
        $this->manager->flush();


        return $this->json([
            "recept created"
        ]);
    }


    #[Route('/recepty', name: 'app_recepty', methods:['get'])]
    public function index(): JsonResponse
    {

        $receptRepository = $this->manager->getRepository(Recept::class);
        
        $filter = array();
        $sort = array();
        $limit = null;
        $offset = null;
        foreach($_GET as $key => $value){
            switch($key){
                case 'sort_by':
                    $param = explode(".", $value);
                    $sort[$param[0]] = $param[1];
                    break;

                case 'ingredience':
                    $recept_ids = $this->manager->getRepository(Ingredience::class)->findReceptyByIngredienceName($value);
                    $filter['id'] = $recept_ids;
                    break;

                case 'limit':
                    $limit = $value;
                    break;

                case 'offset':
                    $offset = $value;
                    break;

                default:
                    $filter[$key] = $value;
            }
        }
       // dd($filter);
        $recepty = $receptRepository->findBy($filter, $sort, $limit, $offset);

        $jsonContent = $this->serializer->serialize($recepty, 'json');

        return $this->json([
            $jsonContent
        ]);
    }


    #[Route('/recepty/{id}', name: 'update_recept', methods:['put', 'patch'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $input = $request->toArray();
        $recept = $this->manager->getRepository(Recept::class)->find($id);

        if(!$recept){
            return $this->json([
                "recept not found for id " . $id
            ]);
        }

        $this->serializer->denormalize($input, Recept::class, null, [AbstractNormalizer::OBJECT_TO_POPULATE => $recept, 'groups' => 'group1']);


        if(isset($input['autor'])){
            $autorRepository = $this->manager->getRepository(Autor::class);
            $autor = $autorRepository->findOneBy(['name' => $input['autor']]);
            if(!$autor){
                $autor = new Autor;
                $autor->setName($input['autor']);
                $this->manager->persist($autor);
            }
            $recept->setAutor($autor);
        }


        if(isset($input['kategorie'])){
            $kategorieRepository = $this->manager->getRepository(Kategorie::class);
            $kategorie = $kategorieRepository->findOneBy(['name' => $input['kategorie']]);
            if(!$kategorie){
                $kategorie = new Kategorie;
                $kategorie->setName($input['kategorie']);
                $this->manager->persist($kategorie);
            }
            $recept->setKategorie($kategorie);
        }


        if(isset($input['nastroje'])){
            $nastrojRepository = $this->manager->getRepository(Nastroj::class);
            foreach($input['nastroje'] as $nastroj_name){
                $nastroj = $nastrojRepository->findOneBy(['name' => $nastroj_name]);
                if(!$nastroj){
                    $nastroj = new Nastroj;
                    $nastroj->setName($nastroj_name);
                    $this->manager->persist($nastroj);
                }
                $recept->addNastroje($nastroj);
            }
        }

        
        if(isset($input['ingredience'])){
            $ingredienceRepository = $this->manager->getRepository(Ingredience::class);
            foreach($input['ingredience'] as $ingredience_name){
                $ingredience = $ingredienceRepository->findOneBy(['name' => $ingredience_name]);
                if(!$ingredience){
                    $ingredience = new Ingredience;
                    $ingredience->setName($ingredience_name);
                    $this->manager->persist($ingredience);
                }
                $recept->addIngredience($ingredience);
            }
        }


        $errors = $this->validator->validate($recept);
        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->json([
                $errorsString
            ]);
        }

        $this->manager->flush();

        return $this->json([
            "recept updated"
        ]);
    }


    #[Route('/recepty/{id}', name: 'delete_recept', methods:['delete'])]
    public function delete(int $id): JsonResponse
    {
        
        $recept = $this->manager->getRepository(Recept::class)->find($id);

        if(!$recept){
            return $this->json([
                "recept not found for id " . $id
            ]);
        }

        $this->manager->remove($recept);
        $this->manager->flush();

        return $this->json([
            "recept deleted"
        ]);
    }
}
