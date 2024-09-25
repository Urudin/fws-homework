<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Bmatovu\LaravelXml\Http\XmlResponse;

class ProductService
{
    public function importCSV(UploadedFile $file)
    {
        //read csv file and skip data
        $handle = fopen($file->getPathName(), 'r');

        //skip the header row
        fgetcsv($handle);

        $chunksize = Product::CHUNK_SIZE;
        while (!feof($handle)) {
            $chunkdata = [];

            for ($i = 0; $i < $chunksize; $i++) {
                $data = fgetcsv($handle);
                if ($data === false) {
                    break;
                }
                $chunkdata[] = $data;
            }

            $this->insertChunk($chunkdata);
        }
        fclose($handle);

        return redirect()->route('home')->with('success', 'Termékek betöltve.');
    }

    /**
     * @param array $chunkData
     * @return void
     * Inserts chunk
     */
    public function insertChunk(array $chunkData): void
    {
        $categories = $this->refreshCategories($chunkData);
        foreach ($chunkData as $column) {
            $insertData = [
                'name' => $column[0],
                'price' => $column[1],
            ];
            $product = Product::query()->updateOrCreate($insertData, ['name' => $insertData['name']]);

            for($i = 2; $i <=4; $i++){
                if(!empty($column[$i])){
                    $product->categories()->attach($categories[$column[$i]]);
                }
            }
        }
    }

    /**
     * @param $chunkData
     * @return Collection
     * Does the refresh of the categories table for the chunk with a single sql query
     */
    public function refreshCategories(array $chunkData): Collection
    {
        $categories = [];
        foreach ($chunkData as $column) {
            $categories = array_merge($categories, [$column[2] ?? null, $column[3] ?? null, $column[4] ?? null]);
        }
        $categories = array_filter(array_unique($categories));
        $categories = array_map(function ($category) {
            return ['name' => $category];
        }, $categories);
        Category::query()->upsert($categories, ['name']);
        return Category::query()->pluck('id', 'name');
    }

    public function returnAsXml() :XmlResponse
    {
        $result = ['products' => []];
        $products = Product::query()->with('categories')->get();
        foreach ($products as $product) {
            $categories = [];
            foreach($product->categories as $category){
                $categories['category'][] = $category->name;
            }
            $result['products'][] = [
                'product' => [
                    'title' => $product->name,
                    'price' => $product->price,
                    'categories' => $categories,
                ]
            ];
        }

        return response()->xml($result);
    }
}
