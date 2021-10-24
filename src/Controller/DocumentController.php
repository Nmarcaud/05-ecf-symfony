<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/document")
 */

class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document")
     */
    public function index(): Response
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    /**
     * @Route("/{document_id}/delete", name="document_delete")
     */
    public function delete($document_id, DocumentRepository $documentRepository, EntityManagerInterface $em): Response
    {
        $document = $documentRepository->find($document_id);

        // Je chope le user id avent supression pour redirect
        $userIdToRedirect = $document->getUser()->getId();

        // Suppression in folder !
        // Sources : https://youtu.be/jrca6I-sBNM
        $fileLink = $this->getParameter("document_directory").'/'.$document->getFile();
        // Si fichier existe bien
        if(file_exists($fileLink)) {
            unlink($fileLink);          // Suppression du fichier
        }

        // Delete en base
        $em->remove($document);
        $em->flush();
        
        return $this->redirect($this->generateUrl('profil', array('id' => $userIdToRedirect)));
    }
}
