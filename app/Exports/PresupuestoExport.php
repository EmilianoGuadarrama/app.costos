<?php
namespace App\Exports;

use App\Models\ObraIniciada;
use App\Models\Bloque;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PresupuestoExport implements WithTitle, WithEvents
{
    protected $obraId;

    // ── Paleta de colores (igual a la plantilla) ──────────────────────────
    const COLOR_NEGRO        = '111111'; // encabezados de bloque fondo
    const COLOR_GRIS_CLARO   = 'EEEEEE'; // columnas G, H, I (iniciales/IVA)
    const COLOR_GRIS_SUBTOT  = 'CCCCCC'; // subtotales de bloque
    const COLOR_BLANCO       = 'FFFFFF';
    const COLOR_TOTAL_FONDO  = 'EEEEEE'; // fila totales finales
    const COLOR_GRAN_TOTAL   = '111111'; // gran total fondo
    const COLOR_TEXTO_BLANCO = 'FFFFFF';
    const COLOR_TEXTO_NEGRO  = '000000';

    public function __construct(int $obraId)
    {
        $this->obraId = $obraId;
    }

    public function title(): string
    {
        return 'PRESUPUESTO';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->buildSheet($event->sheet->getDelegate());
            },
        ];
    }

    // ════════════════════════════════════════════════════════════════════════
    // CONSTRUCCIÓN COMPLETA DE LA HOJA
    // ════════════════════════════════════════════════════════════════════════
    private function buildSheet(Worksheet $sheet): void
    {
        // ── Cargar datos ──────────────────────────────────────────────────
        $obra = ObraIniciada::with([
            'datosDeObra.direccion',
            'cliente.direccionFiscal',
            'obraConceptos.concepto.unidadMedida',
            'totalBloque',
        ])->findOrFail($this->obraId);

        $bloques          = Bloque::orderBy('id')->get();
        $totalesPorBloque = $obra->totalBloque->keyBy('id_bloque');

        // ── Variables de datos ────────────────────────────────────────────
        $datosObra    = $obra->datosDeObra;
        $cliente      = $obra->cliente;
        $direccion    = $cliente?->direccionFiscal ?? $datosObra?->direccion ?? null;
        $domicilio1   = trim(($direccion?->calle_y_numero ?? '') . ', ' . ($direccion?->colonia ?? ''));
        $domicilio2   = trim(($direccion?->delegacion ?? '') . ', ' . ($direccion?->ciudad ?? '') . ', México');
        $domicilio1   = rtrim($domicilio1, ', ');
        $domicilio2   = rtrim($domicilio2, ', ');

        $fechaInicio  = $obra->fecha_inicio ? $obra->fecha_inicio->format('d/m/Y') : '—';
        $fechaEntrega = '—';
        if ($obra->fecha_inicio && $obra->duracion) {
            $fechaEntrega = $obra->fecha_inicio->addDays((int)$obra->duracion)->format('d/m/Y');
        }
        $diasFaltan    = $obra->dias_faltan ?? null;
        $nombreCliente = $cliente?->nombre ?? $cliente?->nombre_o_razon_social ?? '—';
        $nombreObra    = $datosObra?->nombre ?? "Obra #{$obra->id}";
        $duracion      = $obra->duracion ? $obra->duracion . ' días' : '—';
        $folio         = str_pad($obra->id, 4, '0', STR_PAD_LEFT);

        // ── ANCHOS DE COLUMNA (basados en plantilla) ──────────────────────
        // A=días/código, B=duración, C=concepto, D=P.U., E=cant, F=unidad, G=inicial, H=iva, I=total
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(21.5);
        $sheet->getColumnDimension('C')->setWidth(38.66);
        $sheet->getColumnDimension('D')->setWidth(14.66);
        $sheet->getColumnDimension('E')->setWidth(14.66);
        $sheet->getColumnDimension('F')->setWidth(15.41);
        $sheet->getColumnDimension('G')->setWidth(16.5);
        $sheet->getColumnDimension('H')->setWidth(16.5);
        $sheet->getColumnDimension('I')->setWidth(16.5);

        // Fuente por defecto de la hoja
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        // ════════════════════════════════════════════════════════════════
        // BLOQUE 1: ENCABEZADO EMPRESA (filas 1-3 vacías de margen, 4-8)
        // ════════════════════════════════════════════════════════════════

        // Fila 1-3: reservadas para logo (altura pequeña)
        $sheet->getRowDimension(1)->setRowHeight(13.5);
        $sheet->getRowDimension(2)->setRowHeight(13.5);
        $sheet->getRowDimension(3)->setRowHeight(13.5);

        // Fila 4: Nombre empresa
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->setCellValue('A4', 'AKIRAKA ESTUDIO');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(13)->setName('Calibri');

        // Fila 5-8: Datos empresa
        $sheet->getRowDimension(5)->setRowHeight(13.5);
        $sheet->getRowDimension(6)->setRowHeight(13.5);
        $sheet->getRowDimension(7)->setRowHeight(13.5);
        $sheet->getRowDimension(8)->setRowHeight(13.5);
        $sheet->setCellValue('A5', 'Parque Santa María 10, Santa María Ahuacatlán,');
        $sheet->setCellValue('A6', '51200 Valle de Bravo, Estado de México');
        $sheet->setCellValue('A7', 'Cel. 722 165 5901');
        $sheet->setCellValue('A8', 'C.E: administracion@akirakastudio.com');
        foreach (['A5','A6','A7','A8'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setSize(11)->setName('Calibri');
        }

        // Número de folio en I4 (esquina superior derecha)
        $sheet->mergeCells('G4:I8');
        $sheet->setCellValue('G4', "PRESUPUESTO\nFOLIO: {$folio}");
        $this->applyStyle($sheet, 'G4:I8', [
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_BLANCO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        // ════════════════════════════════════════════════════════════════
        // BLOQUE 2: DATOS CLIENTE / PROYECTO (filas 10-16)
        // ════════════════════════════════════════════════════════════════

        // Fila 9: separador visual (vacía)
        $sheet->getRowDimension(9)->setRowHeight(4);

        // Fila 10: etiqueta CLIENTE + label FALTAN DÍAS (en I)
        $sheet->getRowDimension(10)->setRowHeight(13.5);
        $sheet->setCellValue('A10', 'CLIENTE:');
        $sheet->getStyle('A10')->getFont()->setSize(8)->setName('Calibri');

        $sheet->mergeCells('I10:I11');
        $faltan = ($diasFaltan !== null) ? $diasFaltan : '—';
        $sheet->setCellValue('I10', "FALTAN\n{$faltan}");
        $this->applyStyle($sheet, 'I10:I11', [
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        // Fila 11: nombre cliente
        $sheet->getRowDimension(11)->setRowHeight(13.5);
        $sheet->mergeCells('A11:C11');
        $sheet->setCellValue('A11', $nombreCliente);
        $sheet->getStyle('A11')->getFont()->setSize(11)->setName('Calibri');
        $this->applyBorder($sheet, 'A11:H11', Border::BORDER_THIN, 'CCCCCC');

        // Fila 12: etiqueta DOMICILIO
        $sheet->getRowDimension(12)->setRowHeight(13.5);
        $sheet->setCellValue('A12', 'DOMICILIO:');
        $sheet->getStyle('A12')->getFont()->setSize(8)->setName('Calibri');

        // I12:I15 DÍAS (faltan info)
        $sheet->mergeCells('I12:I15');
        $sheet->setCellValue('I12', "{$fechaEntrega}\n{$fechaInicio}");
        $this->applyStyle($sheet, 'I12:I15', [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        // Fila 13: domicilio línea 1
        $sheet->getRowDimension(13)->setRowHeight(13.5);
        $sheet->mergeCells('A13:C13');
        $sheet->setCellValue('A13', $domicilio1 ?: $domicilio2);
        $sheet->getStyle('A13')->getFont()->setSize(11)->setName('Calibri');
        $this->applyBorder($sheet, 'A13:H13', Border::BORDER_THIN, 'CCCCCC');

        // Fila 14: domicilio línea 2 o nombre obra
        $sheet->getRowDimension(14)->setRowHeight(13.5);
        $sheet->mergeCells('A14:C14');
        $sheet->setCellValue('A14', $domicilio2 ?: $nombreObra);
        $sheet->getStyle('A14')->getFont()->setSize(11)->setName('Calibri');
        $this->applyBorder($sheet, 'A14:H14', Border::BORDER_THIN, 'CCCCCC');

        // Fila 15: etiquetas fecha
        $sheet->getRowDimension(15)->setRowHeight(13.5);
        $sheet->setCellValue('A15', 'FECHA INICIO');
        $sheet->setCellValue('B15', 'ENTREGA ESTIMADA');
        $sheet->getStyle('A15')->getFont()->setSize(8)->setName('Calibri');
        $sheet->getStyle('B15')->getFont()->setSize(8)->setName('Calibri');

        // Fila 16: valores de fecha + label DÍAS
        $sheet->getRowDimension(16)->setRowHeight(13.5);
        $sheet->setCellValue('A16', $fechaInicio);
        $sheet->setCellValue('B16', $fechaEntrega);
        $sheet->setCellValue('I16', 'DÍAS');
        $sheet->getStyle('A16')->getFont()->setSize(11)->setName('Calibri');
        $sheet->getStyle('B16')->getFont()->setSize(11)->setName('Calibri');
        $this->applyStyle($sheet, 'I16', [
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Fila 17: etiqueta "SIN IVA" (sobre columnas G)
        $sheet->getRowDimension(17)->setRowHeight(13.5);
        $sheet->mergeCells('G17:G17');
        $sheet->setCellValue('G17', 'SIN IVA');
        $this->applyStyle($sheet, 'G17', [
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ════════════════════════════════════════════════════════════════
        // FILA 18: RESUMEN GENERAL (TOTAL + importes globales)
        // ════════════════════════════════════════════════════════════════
        $sheet->getRowDimension(18)->setRowHeight(19.5);

        // Calcular totales generales anticipado
        $granSubtotal = 0;
        $granIva      = 0;
        foreach ($bloques as $b) {
            $tot = $totalesPorBloque[$b->id] ?? null;
            if ($tot) {
                $granSubtotal += $tot->total;
                $granIva      += $tot->iva;
            }
        }
        $granTotal = $granSubtotal + $granIva;

        $sheet->setCellValue('B18', 'DÍAS');
        $sheet->setCellValue('F18', 'TOTAL');
        $sheet->setCellValue('G18', $granSubtotal);
        $sheet->setCellValue('H18', $granIva);
        $sheet->setCellValue('I18', $granTotal);

        $this->applyStyle($sheet, 'B18', [
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $this->applyStyle($sheet, 'F18', [
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        foreach (['G18','H18','I18'] as $cell) {
            $this->applyStyle($sheet, $cell, [
                'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'numberFormat' => ['formatCode' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE],
            ]);
        }
        $sheet->getStyle('G18:I18')->getNumberFormat()->setFormatCode('"$"#,##0.00');

        // ════════════════════════════════════════════════════════════════
        // FILA 19: ENCABEZADOS DE COLUMNA DE LA TABLA
        // ════════════════════════════════════════════════════════════════
        $sheet->getRowDimension(19)->setRowHeight(19.5);
        $headers = [
            'A19' => 'DÍAS',
            'B19' => 'DURACIÓN',
            'C19' => 'CONCEPTO',
            'D19' => 'P.U.',
            'E19' => 'CANTIDAD',
            'F19' => 'UNIDAD',
            'G19' => 'INICIAL',
            'H19' => 'IVA',
            'I19' => 'TOTAL INICIAL',
        ];
        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }
        $this->applyStyle($sheet, 'A19:F19', [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_BLANCO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]],
        ]);
        $this->applyStyle($sheet, 'G19:I19', [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]],
        ]);

        // ════════════════════════════════════════════════════════════════
        // FILAS DE DATOS: BLOQUES + CONCEPTOS
        // ════════════════════════════════════════════════════════════════
        $currentRow  = 20;
        $diaAcumulado = 0;
        $bloqueNum   = 0;

        foreach ($bloques as $b) {
            $conceptos = $obra->obraConceptos->where('id_bloque', $b->id);
            if ($conceptos->isEmpty()) continue;

            $totBloque = $totalesPorBloque[$b->id] ?? null;
            $bloqueNum++;

            // ── Fila de encabezado del bloque ────────────────────────────
            $sheet->getRowDimension($currentRow)->setRowHeight(19.5);
            $sheet->setCellValue("A{$currentRow}", $bloqueNum);
            $sheet->mergeCells("B{$currentRow}:C{$currentRow}");
            $sheet->setCellValue("B{$currentRow}", strtoupper($b->descripcion));
            $sheet->setCellValue("F{$currentRow}", 'TOTAL ' . strtoupper($b->descripcion));

            if ($totBloque) {
                $sheet->setCellValue("G{$currentRow}", $totBloque->total);
                $sheet->setCellValue("H{$currentRow}", $totBloque->iva);
                $sheet->setCellValue("I{$currentRow}", $totBloque->total_final ?? ($totBloque->total + $totBloque->iva));
            }

            // Estilos encabezado bloque: fondo #111111, texto blanco
            $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_BLANCO]],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_NEGRO]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]],
            ]);
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // G, H, I del encabezado bloque: mismo fondo negro, moneda
            foreach (["G{$currentRow}", "H{$currentRow}", "I{$currentRow}"] as $cell) {
                $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('"$"#,##0.00');
            }

            $currentRow++;

            // ── Filas de conceptos ────────────────────────────────────────
            foreach ($conceptos as $i => $oc) {
                $durDias       = $oc->concepto?->duracion_en_dias ?? 0;
                $diaAcumulado += $durDias;
                $isAlt         = ($i % 2 !== 0);
                $fillColor     = $isAlt ? 'F5F5F5' : self::COLOR_BLANCO;
                $rowH          = 14; // altura base; si el concepto es largo usar 28

                $descripcion = $oc->concepto?->descripcion ?? '—';
                if (strlen($descripcion) > 50) $rowH = 28;

                $sheet->getRowDimension($currentRow)->setRowHeight($rowH);

                $sheet->setCellValue("A{$currentRow}", $diaAcumulado > 0 ? $diaAcumulado : '—');
                $sheet->setCellValue("B{$currentRow}", $durDias > 0 ? $durDias . 'd' : '—');
                $sheet->setCellValue("C{$currentRow}", $descripcion);
                $sheet->setCellValue("D{$currentRow}", $oc->precio_unitario);
                $sheet->setCellValue("E{$currentRow}", $oc->cantidad);
                $sheet->setCellValue("F{$currentRow}", $oc->concepto?->unidadMedida?->abreviatura ?? '—');
                $sheet->setCellValue("G{$currentRow}", $oc->subtotal);
                $sheet->setCellValue("H{$currentRow}", $oc->iva ?? 0);
                $sheet->setCellValue("I{$currentRow}", $oc->total_final ?? $oc->subtotal);

                // Estilos fila concepto
                $this->applyStyle($sheet, "A{$currentRow}:F{$currentRow}", [
                    'font'      => ['bold' => false, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $fillColor]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                ]);
                $this->applyStyle($sheet, "G{$currentRow}:I{$currentRow}", [
                    'font'      => ['bold' => false, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                ]);

                // Alineaciones específicas
                $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$currentRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setWrapText(true);
                $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Formato moneda en D, G, H, I
                $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

                // Alineación derecha para monedas
                foreach (["D{$currentRow}", "G{$currentRow}", "H{$currentRow}", "I{$currentRow}"] as $mc) {
                    $sheet->getStyle($mc)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                $currentRow++;
            }
        }

        // ════════════════════════════════════════════════════════════════
        // TOTALES FINALES
        // ════════════════════════════════════════════════════════════════

        // Subtotal presupuesto
        $sheet->getRowDimension($currentRow)->setRowHeight(19.5);
        $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'SUBTOTAL PRESUPUESTO');
        $sheet->setCellValue("G{$currentRow}", $granSubtotal);
        $sheet->setCellValue("H{$currentRow}", '—');
        $sheet->setCellValue("I{$currentRow}", $granSubtotal);
        $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]],
        ]);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $currentRow++;

        // IVA
        $sheet->getRowDimension($currentRow)->setRowHeight(19.5);
        $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'I.V.A. (16%)');
        $sheet->setCellValue("G{$currentRow}", '—');
        $sheet->setCellValue("H{$currentRow}", $granIva);
        $sheet->setCellValue("I{$currentRow}", $granIva);
        $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8E8E8']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]],
        ]);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $currentRow++;

        // GRAN TOTAL
        $sheet->getRowDimension($currentRow)->setRowHeight(24);
        $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'GRAN TOTAL');
        $sheet->setCellValue("G{$currentRow}", '');
        $sheet->setCellValue("H{$currentRow}", '');
        $sheet->setCellValue("I{$currentRow}", $granTotal);
        $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => self::COLOR_TEXTO_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_NEGRO]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]],
        ]);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
        $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $currentRow++;

        // Fila de nota al pie
        $currentRow++;
        $sheet->getRowDimension($currentRow)->setRowHeight(13.5);
        $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 
            'NOTAS: Los precios están expresados en Pesos Mexicanos (MXN). Este presupuesto tiene vigencia de 30 días. ' .
            'Elaborado: ' . now()->format('d/m/Y')
        );
        $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
            'font'      => ['bold' => false, 'size' => 8, 'color' => ['rgb' => '555555']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => ['top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);

        // ════════════════════════════════════════════════════════════════
        // LISTA DE MATERIALES
        // ════════════════════════════════════════════════════════════════
        $currentRow += 3;
        
        $materialesPorNivelArea = [];
        $obra->load('obraConceptos.materiales.material.unidadMedida', 'obraConceptos.nivel', 'obraConceptos.area');
        foreach ($obra->obraConceptos as $oc) {
            if ($oc->materiales->isEmpty()) continue;
            $nivelId = $oc->id_nivel ?: 0;
            $areaId = $oc->id_area ?: 0;

            if (!isset($materialesPorNivelArea[$nivelId])) {
                $materialesPorNivelArea[$nivelId] = ['nombre' => $oc->nivel ? $oc->nivel->descripcion : 'GENERAL / SIN NIVEL', 'areas' => []];
            }
            if (!isset($materialesPorNivelArea[$nivelId]['areas'][$areaId])) {
                $materialesPorNivelArea[$nivelId]['areas'][$areaId] = ['nombre' => $oc->area ? $oc->area->descripcion : 'Sin Área', 'materiales' => []];
            }

            foreach ($oc->materiales as $mat) {
                if (!$mat->material) continue;
                $matId = $mat->id_material;
                if (!isset($materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId])) {
                    $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId] = ['material' => $mat->material, 'cantidad_total' => 0, 'costo_total' => 0];
                }
                $cant_req = $mat->cantidad * $oc->cantidad;
                $costo    = $mat->precio_unitario * $cant_req;

                $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId]['cantidad_total'] += $cant_req;
                $materialesPorNivelArea[$nivelId]['areas'][$areaId]['materiales'][$matId]['costo_total']    += $costo;
            }
        }

        if (!empty($materialesPorNivelArea)) {
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'LISTA DE MATERIALES A UTILIZAR POR NIVEL Y ÁREA');
            $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $currentRow++;

            // Encabezado de tabla de materiales
            $sheet->getRowDimension($currentRow)->setRowHeight(18);
            $sheet->mergeCells("A{$currentRow}:C{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'MATERIAL');
            $sheet->mergeCells("D{$currentRow}:F{$currentRow}");
            $sheet->setCellValue("D{$currentRow}", 'CANTIDAD TOTAL');
            $sheet->mergeCells("G{$currentRow}:I{$currentRow}");
            $sheet->setCellValue("G{$currentRow}", 'COSTO ESTIMADO');

            $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_BLANCO]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_NEGRO]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]],
            ]);
            $currentRow++;

            $granTotalMateriales = 0;

            foreach ($materialesPorNivelArea as $nivelId => $nivelData) {
                // Fila de nivel
                $sheet->getRowDimension($currentRow)->setRowHeight(18);
                $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
                $sheet->setCellValue("A{$currentRow}", mb_strtoupper($nivelData['nombre']));
                $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_BLANCO]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                ]);
                $currentRow++;

                foreach ($nivelData['areas'] as $areaId => $areaData) {
                    if (empty($areaData['materiales'])) continue;

                    // Fila de area
                    $sheet->getRowDimension($currentRow)->setRowHeight(16);
                    $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
                    $sheet->setCellValue("A{$currentRow}", '   ' . strtoupper($areaData['nombre']));
                    $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                        'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F0F0F0']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                    ]);
                    $currentRow++;

                    foreach ($areaData['materiales'] as $matId => $data) {
                        $granTotalMateriales += $data['costo_total'];
                        
                        $sheet->getRowDimension($currentRow)->setRowHeight(15);
                        $sheet->mergeCells("A{$currentRow}:C{$currentRow}");
                        $sheet->setCellValue("A{$currentRow}", '      ' . mb_strtoupper($data['material']->nombre));
                        
                        $sheet->mergeCells("D{$currentRow}:F{$currentRow}");
                        $sheet->setCellValue("D{$currentRow}", number_format($data['cantidad_total'], 2) . ' ' . ($data['material']->unidadMedida?->abreviatura ?? ''));

                        $sheet->mergeCells("G{$currentRow}:I{$currentRow}");
                        $sheet->setCellValue("G{$currentRow}", $data['costo_total']);

                        $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                            'font' => ['bold' => false, 'size' => 11, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_BLANCO]],
                            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'EEEEEE']]],
                        ]);
                        
                        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

                        $currentRow++;
                    }
                }
            }

            // Total de materiales
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'TOTAL ESTIMADO EN MATERIALES:');
            $sheet->mergeCells("G{$currentRow}:I{$currentRow}");
            $sheet->setCellValue("G{$currentRow}", $granTotalMateriales);

            $this->applyStyle($sheet, "A{$currentRow}:I{$currentRow}", [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => self::COLOR_TEXTO_NEGRO]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => self::COLOR_GRIS_CLARO]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]],
            ]);
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            $currentRow++;
        }

        // ════════════════════════════════════════════════════════════════
        // LOGO DE LA EMPRESA
        // ════════════════════════════════════════════════════════════════
        $logoPath = public_path('img/logo_akiraka.jpeg');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo Akiraka');
            $drawing->setDescription('Logo de la empresa');
            $drawing->setPath($logoPath);
            $drawing->setHeight(65);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(4);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        }

        // ════════════════════════════════════════════════════════════════
        // CONFIGURACIÓN DE IMPRESIÓN
        // ════════════════════════════════════════════════════════════════
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);
        // FitToWidth=1 ajusta al ancho de la página; FitToHeight=0 sin límite vertical
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0);
        $pageSetup->setFitToPage(true); // debe setearse DESPUÉS de fitToWidth/Height

        $margins = $sheet->getPageMargins();
        $margins->setTop(0.75);
        $margins->setBottom(0.75);
        $margins->setLeft(0.7);
        $margins->setRight(0.7);
        $margins->setHeader(0);
        $margins->setFooter(0);

        // Títulos de página: filas 18-19 se repiten en cada página impresa
        $pageSetup->setRowsToRepeatAtTopByStartAndEnd(18, 19);

        // Área de impresión
        $lastDataRow = $currentRow;
        $pageSetup->setPrintArea("A1:I{$lastDataRow}");

        // ════════════════════════════════════════════════════════════════
        // BORDE EXTERIOR GENERAL de toda la tabla de datos (fila 18 a final)
        // ════════════════════════════════════════════════════════════════
        $sheet->getStyle("A18:I{$lastDataRow}")->getBorders()->getOutline()
            ->setBorderStyle(Border::BORDER_MEDIUM)
            ->getColor()->setRGB('111111');

        // Borde en datos del cliente (área superior)
        $sheet->getStyle('A10:H16')->getBorders()->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB('AAAAAA');
    }

    // ════════════════════════════════════════════════════════════════════════
    // HELPERS DE ESTILO
    // ════════════════════════════════════════════════════════════════════════
    private function applyStyle(Worksheet $sheet, string $range, array $style): void
    {
        $phpStyle = [];

        if (isset($style['font'])) {
            $phpStyle['font'] = $style['font'];
        }
        if (isset($style['fill'])) {
            $phpStyle['fill'] = $style['fill'];
        }
        if (isset($style['alignment'])) {
            $phpStyle['alignment'] = $style['alignment'];
        }
        if (isset($style['borders'])) {
            $phpStyle['borders'] = $style['borders'];
        }
        if (isset($style['numberFormat'])) {
            $phpStyle['numberFormat'] = $style['numberFormat'];
        }

        $sheet->getStyle($range)->applyFromArray($phpStyle);
    }

    private function applyBorder(Worksheet $sheet, string $range, string $style, string $color): void
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle($style)
            ->getColor()->setRGB($color);
    }
}
