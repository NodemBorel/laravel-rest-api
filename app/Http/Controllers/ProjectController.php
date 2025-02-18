<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Requests\StoreProjectResquest;
use App\Http\Requests\UpdateProjectResquest;

class ProjectController extends Controller
{
    public function index(Request $request){
        $projects = QueryBuilder::for(Project::class)
        ->allowedIncludes('tasks')
        ->paginate();
        
        return new ProjectCollection($projects);
    }

    public function store(StoreProjectResquest $request){
        $validated = $request->validated();

        $project = Auth::user()->projects()->create($validated);

        return new ProjectResource($project);
    }

    public function show(Request $request, Project $project){
        //return new ProjectResource($project);
        return (new ProjectResource($project))->load('tasks')->load('members');
    }

    public function update(UpdateProjectResquest $request, Project $project){
        $validated = $request->validated();

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Request $request, Project $project){
        
        $project->delete();

        return response()->noContent();
    }
}
