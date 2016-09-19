<?php

namespace thgs\base\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use thgs\base\Abstracts\AbstractRepository;

class RController extends Controller
{
    protected $repo;
    protected $viewsDir;

    // Setters -----------------------------------------------------------------

    public function setRepo(AbstractRepository $repo)
    {
        $this->repo = $repo;
    }

    public function setViewsDir($dir)
    {
        $this->viewsDir = $dir;
    }

    // Helper protected methods ------------------------------------------------

    protected function basicRedirect($condition, $message)
    {
        return ($condition)
            ? redirect()->route($this->viewsDir.'.index')->withMessages([$message])
            : redirect()->route($this->viewsDir.'.index')->withErrors([$message]);
    }


    // Resource methods --------------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->viewsDir.'.index', [
            'models' => $this->repo->paginate(25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->viewsDir.'.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create model
        $model = $this->repo->create($request->except('_token'));

        // construct message
        $msg = ($model)
            ? 'Created successfully'
            : 'Error during creation';

        // return basic redirect
        return $this->basicRedirect(($model), $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view($this->viewsDir.'.show', [
            'model' => $this->repo->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view($this->viewsDir.'.edit', [
            'model' => $this->repo->findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // perform update
        $result = $this->repo->update($id, $request->except('_token'));

        // construct message
        $msg = ($result)
            ? 'Updated successfully'
            : 'Error during update';

        // return basic redirect
        return $this->basicRedirect(($result), $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // pass to repo for delete
        $result = $this->repo->delete($id);

        // construct message
        $msg = ($result)
            ? 'Deleted successfully'
            : 'Error during delete';

        // return basic redirect
        return $this->basicRedirect(($result), $msg);
    }

}
