<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends Controller
{

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): View
    {
        $products = $this->productRepository->getAll($request->all());

        return view('home', compact('products'));
    }

    /**
     * @throws Exception
     */
    public function edit($id): View
    {
        $product = $this->productRepository->find($id);

        return view('edit', compact('product'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $request['slug'] = Str::slug($request['name'], '_');
        $this->productRepository->create($request->all());

        return redirect()->route('home.index')->with('message', 'Create successful!');
    }

    public function update(ProductRequest $request, $id): RedirectResponse
    {
        $request['slug'] = Str::slug($request['name'], '_');
        $this->productRepository->update($request->all(), $id);

        return redirect()->route('home.edit', $id)->with('message', 'Update successful!');
    }

    public function destroy($id): RedirectResponse
    {
        $this->productRepository->delete($id);

        return redirect()->route('home.index')->with('message', 'Delete successful!');
    }

    public function delMultiple(Request $request): RedirectResponse
    {
        if (!$request['ids'])
        {
            throw new HttpException('400', 'IDs is invalid');
        }

        $ids = explode(',', $request['ids']);

        $this->productRepository->deleteMultiple($ids);

        return redirect()->route('home.index')->with('message', 'Delete successful!');
    }
}
