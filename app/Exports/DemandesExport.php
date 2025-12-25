<?php

namespace App\Exports;

use App\Models\Demande;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DemandesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $demandes;

    public function __construct($demandes)
    {
        $this->demandes = $demandes;
    }

    public function collection()
    {
        return $this->demandes;
    }

    public function headings(): array
    {
        return [
            'Référence',
            'Type',
            'Demandeur',
            'Statut',
            'Priorité',
            'Date création',
            'Date soumission',
            'Date validation',
        ];
    }

    public function map($demande): array
    {
        return [
            $demande->reference,
            ucfirst(str_replace('_', ' ', $demande->type_demande)),
            $demande->demandeur->full_name ?? '-',
            ucfirst($demande->statut),
            ucfirst($demande->priorite),
            $demande->created_at->format('d/m/Y H:i'),
            $demande->date_soumission ? $demande->date_soumission->format('d/m/Y H:i') : '-',
            $demande->date_validation ? $demande->date_validation->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}


