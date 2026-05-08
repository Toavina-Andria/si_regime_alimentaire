<?php

namespace App\Services;

// Inclusion manuelle de la classe FPDF
require_once APPPATH . 'ThirdParty/fpdf186/fpdf.php';

class ExportPDF
{
    /**
     * Exporte un bilan personnel PDF avec FPDF.
     *
     * @param array $user
     * @param array $stats  (imc, categorie, objectif, abonnement)
     * @param string $filename
     * @return void
     */
    public function exportBilanPersonnel($user, $stats, $filename = 'bilan.pdf')
    {
        // Création du PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 12);

        // Titre
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'NutriPlan - Mon bilan personnel', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 6, 'Genere le : ' . date('d/m/Y H:i:s'), 0, 1, 'C');
        $pdf->Ln(10);

        // Informations utilisateur
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Informations personnelles', 0, 1);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->Cell(40, 7, 'Nom :', 0, 0);
        $pdf->Cell(0, 7, $user['nom'] . ' ' . $user['prenom'], 0, 1);
        $pdf->Cell(40, 7, 'Email :', 0, 0);
        $pdf->Cell(0, 7, $user['email'], 0, 1);
        $pdf->Cell(40, 7, 'Objectif :', 0, 0);
        
        $objectif = $user['objectif'] ?? '';
        $libelle = '';
        if ($objectif === 'augmenter_poids') $libelle = 'Prendre du poids';
        elseif ($objectif === 'reduire_poids') $libelle = 'Perdre du poids';
        elseif ($objectif === 'imc_ideal') $libelle = 'Atteindre l\'IMC ideal';
        else $libelle = 'Non defini';
        $pdf->Cell(0, 7, $libelle, 0, 1);
        
        // IMC
        if (!empty($stats['imc'])) {
            $pdf->Cell(40, 7, 'IMC :', 0, 0);
            $pdf->Cell(0, 7, $stats['imc'] . ' (' . ($stats['categorie'] ?? '') . ')', 0, 1);
        }
        
        // Abonnement
        if (!empty($stats['abonnement'])) {
            $pdf->Cell(40, 7, 'Abonnement :', 0, 0);
            $pdf->Cell(0, 7, $stats['abonnement']['nom'] . ' (' . $stats['abonnement']['statut'] . ')', 0, 1);
        }
        
        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->Cell(0, 6, 'Ce document a ete genere automatiquement par NutriPlan.', 0, 1, 'C');
        
        // Sortie du PDF (téléchargement)
        $pdf->Output('D', $filename);
    }
}