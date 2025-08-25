<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportProductsCsvCommand extends Command
{
    protected static $defaultName = 'app:export-products-csv';
    protected static $defaultDescription = 'Exporte tous les produits vers un fichier CSV.';

    public function __construct(private ProductRepository $productRepository)
    {
        parent::__construct('app:export-products-csv');
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->setHelp('Cette commande permet de faire un export CSV de tous les produits');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->productRepository->findAllOrderedByName();
    
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d_H-i-s');
        $filePath = __DIR__ . "/../../public/exports/products_$date.csv";
        $file = fopen($filePath, 'w');

        if (!$file) {
            $output->writeln('<error>Impossible de créer un fichier CSV.</error>');
            return Command::FAILURE;
        }

        // En-têtes
        fputcsv($file, ['ID', 'Nom', 'Slug', 'Description', 'Prix', 'Image']);

        // Données
        foreach ($products as $product) {
            fputcsv($file, [
                $product->getId(),
                $product->getNom(),
                $product->getSlug(),
                $product->getDescription(),
                $product->getPrix(),
                $product->getImage()
            ]);
        }

        fclose($file);

        $output->writeln('<info>L\'import CSV s`est effectué avec succès ! Fichier: ' . $filePath . '</info>');

        return Command::SUCCESS;
    }
}
