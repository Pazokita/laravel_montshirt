<div class="row">
    <div class="col-12">
        <label for="taille_id">Selectionnez une taille</label>
        <select class="form-control" name="taille_id" id="taille_id">
            @foreach($tailles as $taille)
                @if(in_array($taille->id, $tailles_produit_ids))
                <option disabled value="{{$taille->id}}">{{$taille->nom}}</option>
                @else
                <option value="{{$taille->id}}">{{$taille->nom}}</option>
                @endif
            @endforeach
        </select>

        <div class="row">
            <div class="col-12">
                <label for="qte">Quantité</label>
                <input type="number" min="0" class="form-control" name="qte" id="qte">
            </div>
        </div>
    </div>

