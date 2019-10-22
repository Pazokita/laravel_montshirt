<?php

namespace App\Http\Controllers\Backend;


use App\Categorie;
use App\Http\Controllers\Controller;
use App\Produit;
use App\Tag;
use App\Taille;
use App\Type;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ProduitController extends Controller
{
    //
    public function index()
    {
        $produits = Produit::all();
//        dd($produits);
        return view('backend.produit.index', ['produits' => $produits]);
    }

    public function edit(Request $request)
    {
        $produits = Produit::all();
        $produit = Produit::find($request->id);
        $categories = Categorie::all();
        $tags = Tag::all();
        $tags_id = [];
        foreach ($produit->tags as $t) {
            $tags_id [] = $t->id;
            //dd($tags_id);
        }
        $produit_recommandations = [];
        foreach ($produit->recommandations as $r) {
            $produit_recommandations [] = $r->id;
            //dd($tags_id);
        }
        return view('backend.produit.edit', ['produits' => $produits,
            'categories' => $categories,
            'tags' => $tags, 'produit' => $produit,
            'tags_id' => $tags_id, 'produit_recommandations' => $produit_recommandations
        ]);

    }

    public function add()
    {
        $produits = Produit::all();
        $categories = Categorie::all();
        $tags = Tag::all();

        return view('backend.produit.add', ['produits' => $produits, 'categories' => $categories, 'tags' => $tags]);
    }

//stocker les données du formulaire
    public function store(Request $request)
    {
        $request->validate(
            ['nom' => 'required|max:900',
                'prix_ht' => 'required',
                'description' => 'required',
                'quantite' => 'required',
                'categorie_id' => 'required',
                'photo-principale' => 'required image|max:1999'

            ]);

        if ($request->hasFile('photo_principale')) {
            //récuperer le nom de l'image
            $fileName = $request->file('photo_principale')->getClientOriginalName();
            //Télechargement de l'image
            $request->file('photo_principale')->storeAs('public/upload', $fileName);
            //dd(public_path('upload/' .$fileName));
            $img = Image::make($request->file('photo_principale')->getRealPath());

            /*Insert watermark at bottom-right corner with 10 px offset */
            $img->insert(public_path('img/favicon.png'), 'bottom-right', 10, 10);
            $img->save('storage/upload/' . $fileName);
            // dd('saved image succesfully');

        }
        $produit = new Produit();
        $produit->nom = $request->nom;
        $produit->prix_ht = $request->prix_ht;
        $produit->description = $request->description;
        $produit->photo_principale = $fileName;
        $produit->quantite = $request->quantite;
        $produit->categorie_id = $request->categorie_id;
        $produit->save();

        if ($request->tags) {
            foreach ($request->tags as $id) {
                $produit->tags()->attach($id);
            }
        }
        if ($request->produits_recommandes) {
            foreach ($request->produits_recommandes as $id) {
                $produit->recommandations()->attach($id);
            }
        }


        return redirect()->route('backend_homepage')
            ->with('notice', 'Le produit <strong> ' . $produit->nom . '</strong> a été ajouté');


    }

    public function update(Request $request)
    {
        $produit = Produit::find($request->id);
        $request->validate(
            ['nom' => 'required|max:900',
                'prix_ht' => 'required',
                'description' => 'required',
                'quantite' => 'required',
                'categorie_id' => 'required',
                'photo-principale' => 'required image|max:1999'

            ]);
        if ($request->hasFile('photo_principale')) {
            //récuperer le nom de l'image
            $fileName = $request->file('photo_principale')->getClientOriginalName();
            //Télechargement de l'image
            $request->file('photo_principale')->storeAs('public/upload', $fileName);
            //dd(public_path('upload/' .$fileName));
            $img = Image::make($request->file('photo_principale')->getRealPath());

            /*Insert watermark at bottom-right corner with 10 px offset */
            $img->insert(public_path('img/favicon.png'), 'bottom-right', 10, 10);
            $img->save('storage/upload/' . $fileName);
            $produit->photo_principale = $fileName;
        }

        $produit->nom = $request->nom;
        $produit->prix_ht = $request->prix_ht;
        $produit->description = $request->description;

        $produit->quantite = $request->quantite;
        $produit->categorie_id = $request->categorie_id;
        $produit->save();

//        foreach ($request->tags as $id) {
//            $produit->tags()->attach($id);
//        }
        $produit->tags()->sync($request->tags);
        $produit->recommandations()->sync($request->produits_recommandes);

        return redirect()->route('backend_homepage')
            ->with('notice', 'Le produit <strong> ' . $produit->nom . '</strong> a été modifié');
    }

    public function delete(Request $request)
    {
        $produit = Produit::find($request->id);
        $produit->delete();
        return redirect()->route('backend_homepage')
            ->with('notice', 'Le produit <strong>' . $produit->nom . '</strong> a été supprimé');


    }

//AJouter une taille et un stock
    public function addSize(Request $request)
    {
        $produit = Produit::find($request->id);
        $types = Type::all();
        return view('backend.produit.add_size', ['produit' => $produit, 'types' => $types]);
    }

    //Recuperer les tailles liés au type selectionné (ajax)

    public function selectSizeAjax(Request $request)
    {
        $type_id = $request->type_id;
        $type = Type::find($type_id);
        $produit = Produit::find($request->produit_id);
        $tailles_produit=$produit->tailles;
        $tailles_produit_ids= [];
        foreach ($tailles_produit as $taille_produit){
            $tailles_produit_ids[] = $taille_produit->id;
        }

        //        possible de faire ceci mais c une pensée en base de donnée et non objet : $tailles = Tailles::where('type_id', '=', $type_id)->get();

        return view('backend.produit.select_tailles_ajax', ['tailles' => $type->tailles, 'tailles_produit_ids'=>$tailles_produit_ids]);
    }

//stocker la taille et le produit selectionnée

    public function storeSize(Request $request)
    {
//        dd($request->all());
        $produit= Produit::find($request->id);
//association de la taille et la quantité liées au produit
        $produit->tailles()->attach($request->taille_id, ['qte'=>$request->qte]);

        return redirect()->route('backend_produit_add_size', ['id'=>$produit->id])
            ->with('notice', 'La taille pour le produit <strong>' . $produit->nom . '</strong> a bien été ajouté');
    }

    //Retirer l'association entre une taille et un produit
    public function removeSizeAjax(Request $request){
        $produit= Produit::find($request->produit_id);
        $produit->tailles()->detach($request->taille_id);
        $taille = Taille::find($request->taille_id);
        return 'La taille <strong>' .$taille->nom. ' a été retirée';

    }
}
