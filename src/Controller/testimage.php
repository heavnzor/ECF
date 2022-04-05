<?php

$photo = $form->get('image')->getData();
if ($photo) {
$originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
// this is needed to safely include the file name as part of the URL
$safeFilename = $slugger->slug($originalFilename);
$newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->getExtension();

// Move the file to the directory where brochures are stored
try {
$photo->move(
$this->getParameter('photo_directory'),
$newFilename
);
} catch (FileException $e) {
throw new \RuntimeException($e->getMessage);
}
$photoFile = $form->get('image')->getData();
if ($photoFile) {
$photoFileName = $fileUploader->upload($photoFile);
$cours->setImage($photoFileName);
}

// updates the 'photoname' property to store the PDF file name
// instead of its contents
$cours->setImage($newFilename);
}
$pdf = $form->get('pdf')->getData();
if ($pdf) {
    $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
    // this is needed to safely include the file name as part of the URL
    $safeFilename = $slugger->slug($originalFilename);
    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->getExtension();

    // Move the file to the directory where brochures are stored
    try {
        $pdf->move(
            $this->getParameter('photo_directory'),
            $newFilename
        );
    } catch (FileException $e) {
        throw new \RuntimeException($e->getMessage);
    }
    $pdfFile = $form->get('pdf')->getData();
    if ($pdfFile) {
        $pdfFileName = $fileUploader->upload($pdfFile);
        $cours->setImage($pdfFileName);
    }

    // updates the 'photoname' property to store the PDF file name
    // instead of its contents
    $cours->setPdf($newFilename);
}