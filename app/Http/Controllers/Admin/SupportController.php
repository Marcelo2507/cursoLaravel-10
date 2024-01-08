<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateSupportDTO;
use App\DTO\UpdateSupportDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSupport;
use App\Models\Support;
use App\Services\SupportService;
use Illuminate\Http\Request;

class SupportController extends Controller
{

    public function __construct(
        protected SupportService $service
    )   {}

    public function index(Request $request){

        // $supports = Support::all(); -- Simplificada;
        // $support = new Support(); -- Instancia chamada no construtor;

        // $supports = $support->all();


        $support = $this->service->getAll($request->filter);

        return view('admin/supports/index', compact('supports')); //ou '** ['supports' => $supports] **' vai dar na mesma;
    }

    public function show(string $id){
        //Support::find($id);
        //Support::where('id', $id)->first();
        //Support::where('id', '!=', $id)->first();
        // if (!$support = Support::find($id)) {
        //     return back();
        // }
            if (!$support = $this->service->findOne($id)){
                return back();
            }

        return view('admin/supports/show', compact('support'));
    }

    public function create(){
        return view('admin/supports/create');
    }

    public function store(StoreUpdateSupport $request, Support $support){
        // $data = $request->validated();
        // $data['status'] = 'a';

        // $support = $support->create($data);
        
        $this->service->new(
            CreateSupportDTO::makeFromRequest($request)
        );

        return redirect()->route('supports.index');
    }

    public function edit(string $id){
        // if (!$support = Support::find($id)) {
        //     return back();
        // }

        //if (!$support = $support->where('id', $id)->first()) {
        if (!$support = $this->service->findOne($id)) {
            return back();
        }

        return view('admin/supports.edit', compact('support'));
    }

    public function update(StoreUpdateSupport $request, Support $support, string $id){
        

        // Serve tanto pra cadastro quanto atualização, porém se houver muitas colunas, se tornaria improdutivo;
        // $support->subject = $request->subject;
        // $support->body = $request->body;
        // $support->save();

        // $support->update($request->only([
        //     'subject', 'body'
        // ]));

        $support = $this->service->update(
            UpdateSupportDTO::makeFromRequest($request)
        );
        if (!$support) {
            return back();
        }

        return redirect()->route('supports.index');

    }

    public function destroy(string $id){
        // if (!$support = Support::find($id)){
        //     return back();
        // }

        // $support->delete();

        $this->service->delete($id);

        return redirect()->route('supports.index');
    }
}