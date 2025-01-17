<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectAnalysisController extends Controller
{
    
    public function report()
    {
        return view('ProjectAnalysis.report');
    }

    public function checkBudget()
    {
        return view('ProjectAnalysis.checkBudget');
    }

    public function allProject()
    {
        return view('ProjectAnalysis.allProject');
    }

   



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
