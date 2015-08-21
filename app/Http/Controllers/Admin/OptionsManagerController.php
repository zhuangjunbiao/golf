<?php namespace App\Http\Controllers\Admin;

use App\Models\Options;
use Illuminate\Http\Request;

class OptionsManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Options $modOptions
     * @return \Illuminate\View\View
     */
    public function index(Request $request, Options $modOptions)
    {
        $list = $modOptions->all();
        $data = array(
            'list'  => $list
        );

        return view('admin.options.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
