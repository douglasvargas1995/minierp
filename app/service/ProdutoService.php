<?php

class ProdutoService
{
   
    public static function gerarBarcode($produto_id)
    {
        $produto = new Produto($produto_id);
        
        if($produto->cod_barras)
        {
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($produto->cod_barras, $generator::TYPE_CODE_128, 5, 100);
            $file = "tmp/produto_barcode_{$produto_id}.png";
            file_put_contents($file, $barcode);
            
            return $file;    
        }
        
        return '';
    }
    
    public static function gerarQrCode($produto_id)
    {
        $produto = new Produto($produto_id);
        
        if($produto->cod_barras)
        {
            $file = "tmp/produto_qrcode_{$produto_id}.png";

            $renderer = new \BaconQrCode\Renderer\Image\Png;
            $renderer->setHeight( 150 );
            $renderer->setWidth( 150 );
            $renderer->setMargin(0);
        
            $writer = new \BaconQrCode\Writer($renderer);
            $writer->writeFile($produto->cod_barras, $file);
            
            return $file;    
        }
        
        return '';
    }
   
}
