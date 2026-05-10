<?php

namespace App\Services;

require_once APPPATH . 'ThirdParty/fpdf186/fpdf.php';

class BilanPDF extends \FPDF
{
    public function Header()
    {
        $this->SetFillColor(45, 106, 79);
        $this->Rect(0, 0, 210, 35, 'F');

        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 12, 'NutriPlan - Bilan Personnel', 0, 1, 'C');
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(0, 6, 'Genere le ' . date('d/m/Y H:i'), 0, 1, 'C');
        $this->Ln(8);

        $this->SetDrawColor(45, 106, 79);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(4);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 7);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 10, 'NutriPlan - Document genere automatiquement', 0, 0, 'R');
    }

    public function sectionTitle($label)
    {
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetTextColor(45, 106, 79);
        $this->Cell(0, 8, $label, 0, 1);
        $this->SetDrawColor(45, 106, 79);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(3);
    }

    public function infoRow($label, $value, $w1 = 50)
    {
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor(80, 80, 80);
        $this->Cell($w1, 7, $label, 0, 0);
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetTextColor(30, 30, 30);
        $this->Cell(0, 7, $value, 0, 1);
    }
}

class ExportPDF
{
    public function exportBilanPersonnel($user, $stats, $filename = 'bilan.pdf')
    {
        $pdf = new BilanPDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // --- Section utilisateur ---
        $pdf->sectionTitle('Informations personnelles');

        $pdf->infoRow('Nom complet :', $user['nom'] . ' ' . $user['prenom']);
        $pdf->infoRow('Email :', $user['email']);
        $pdf->infoRow('Telephone :', $user['telephone'] ?? '—');
        $pdf->infoRow('Adresse :', $user['adresse'] ?? '—');
        if (!empty($user['taille_cm'])) {
            $pdf->infoRow('Taille :', $user['taille_cm'] . ' cm');
        }
        if (!empty($user['poids_kg'])) {
            $pdf->infoRow('Poids actuel :', $user['poids_kg'] . ' kg');
        }

        $objectif = $user['objectif'] ?? '';
        $libelle = match ($objectif) {
            'augmenter_poids' => 'Prendre du poids',
            'reduire_poids'   => 'Perdre du poids',
            'imc_ideal'       => 'Atteindre l\'IMC ideal',
            default           => 'Non defini',
        };
        $pdf->infoRow('Objectif :', $libelle);

        $pdf->Ln(4);

        // --- Section IMC ---
        if (!empty($stats['imc'])) {
            $pdf->sectionTitle('Indice de Masse Corporelle (IMC)');

            $imc = $stats['imc'];
            $categorie = $stats['categorie'] ?? '';

            if (str_contains(strtolower($categorie), 'normal') || str_contains(strtolower($categorie), 'ideal')) {
                $color = [45, 106, 79];
            } elseif (str_contains(strtolower($categorie), 'surpoids') || str_contains(strtolower($categorie), 'obesite')) {
                $color = [193, 57, 43];
            } else {
                $color = [212, 168, 83];
            }

            $pdf->SetFillColor($color[0], $color[1], $color[2]);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Helvetica', 'B', 14);

            $boxX = 70;
            $boxY = $pdf->GetY();
            $pdf->SetX($boxX);
            $pdf->Cell(70, 14, ' IMC : ' . $imc, 0, 0, 'C', true);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(0, 14, '  (' . $categorie . ')', 0, 1);

            $pdf->Ln(4);
        }

        // --- Section Abonnement ---
        if (!empty($stats['abonnement'])) {
            $pdf->sectionTitle('Abonnement');

            $abonnement = $stats['abonnement'];
            $pdf->infoRow('Formule :', $abonnement['nom']);
            $pdf->infoRow('Statut :', $abonnement['statut'] ?? 'Actif');

            if (!empty($abonnement['date_debut'])) {
                $pdf->infoRow('Date de debut :', date('d/m/Y', strtotime($abonnement['date_debut'])));
            }
            if (!empty($abonnement['date_fin'])) {
                $pdf->infoRow('Date de fin :', date('d/m/Y', strtotime($abonnement['date_fin'])));
            }
        } else {
            $pdf->sectionTitle('Abonnement');
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell(0, 7, 'Aucun abonnement actif', 0, 1);
        }

        $pdf->Ln(8);

        // --- Footer message ---
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->MultiCell(0, 4, 'Ce bilan a ete genere automatiquement par NutriPlan. Les informations fournies sont basees sur les donnees saisies par l\'utilisateur et ne constituent en aucun cas un avis medical.');

        $pdf->Output('D', $filename);
    }
}
