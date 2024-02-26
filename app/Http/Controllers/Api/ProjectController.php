<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // ? EAGER LOADING con il nome del metodo presente all'interno del model
        $projects = Project::with('title', 'author', 'date')->paginate(20);
        return response()->json(
            [
                "success" => true,
                "results" => $projects,
            ]
        );
    }

    public function show(Project $project)
    {
        return response()->json([
            "success" => true,
            "results" => $project
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->all();

        if (isset($data['title'])) {
            $keyword = $data['title'];
            $projects = Project::where('title', 'LIKE', "%{$keyword}%")->get();
        } elseif (is_null($data['title'])) {
            $projects = Project::all();
        } else {
            abort(404);
        }

        return response()->json([
            "success" => true,
            "results" => $projects,
            "matches" => count($projects)
        ]);
    }
}
