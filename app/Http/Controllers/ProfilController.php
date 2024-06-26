<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth('sanctum')->check()) {//Aficher les statuts pour admins
            $profils = Profil::all();
        } else {//Sinon afficher que les profils avec les statuts actif
            $profils = Profil::where('statut', StatutEnum::ACTIF)->get();
            $profils->makeHidden('statut');
        }
        $profils->makeHidden(['created_at', 'updated_at']);//On cache les champs qui nous n'interesse pas

        return response()->json([
            'message' => $profils
        ]);
    }
}
