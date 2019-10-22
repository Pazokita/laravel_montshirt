@extends('shop')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @if($cat->parent !== null)
{{--                afficher les categories des sous categories--}}
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('view_by_cat', ['id'=>$cat->parent->id])}}">
                    {{$cat->parent->nom}}</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{$cat->nom}}
            </li>
            {{--            afficher les sous-categories s'il y en a --}}
            @if($cat->enfants)
                @foreach($cat->enfants as $enfant)
                    <li class="breadcrumb-item"><a
                            href="{{route('view_by_cat', ['id'=>$enfant->id])}}">{{$enfant->nom}}</a>
                    </li>
                @endforeach
            @endif
        </ol>
    </nav>

    <main role="main">

        <div class="py-3">
            <div class="container-fluid">
                <div class="row">
                    @foreach($produits as $produit)
                        <div class="col-md-3">
                            <div class="card mb-4 box-shadow">
                                <a href="{{route('view_product', ['id'=>$produit->id])}}">
                                    <img src="{{asset('storage/upload/'.$produit->photo_principale)}}"
                                         class="card-img-top img-fluid" alt="Responsive image">
                                </a>
                                <div class="card-body">
                                    <p class="card-text">{{$produit->nom}}<br>{{$produit->description}} </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="price">{{$produit->prixTTC()}} €</span>
                                        <a href="{{route('view_product',['id'=>$produit->id])}}" class="btn btn-sm
                                btn-outline-secondary"><i
                                                class="fas
                                fa-eye"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </main>
@endsection
