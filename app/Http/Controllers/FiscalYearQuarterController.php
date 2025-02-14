<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FiscalYearQuarterModel;

class FiscalYearQuarterController extends Controller
{
    public function index()
    {
        $fiscalYearsAndQuarters = FiscalYearQuarterModel::all();
        return view('fiscalYearQuarter.index', compact('fiscalYearsAndQuarters'));
    }

    public function create()
    {
        return view('fiscalYearQuarter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Fiscal_Year' => 'required|integer',
            'Quarter' => 'required|integer|min:1|max:4',
        ]);

        FiscalYearQuarterModel::create($request->all());

        return redirect()->route('fiscalYearQuarter.index')->with('success', 'Fiscal year and quarter created successfully.');
    }

    public function edit($id)
    {
        $fiscalYearQuarter = FiscalYearQuarterModel::findOrFail($id);
        return view('fiscalYearQuarter.edit', compact('fiscalYearQuarter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Fiscal_Year' => 'required|integer',
            'Quarter' => 'required|integer|min:1|max:4',
        ]);

        $fiscalYearQuarter = FiscalYearQuarterModel::findOrFail($id);
        $fiscalYearQuarter->update($request->all());

        return redirect()->route('fiscalYearQuarter.index')->with('success', 'Fiscal year and quarter updated successfully.');
    }

    public function destroy($id)
    {
        $fiscalYearQuarter = FiscalYearQuarterModel::findOrFail($id);
        $fiscalYearQuarter->delete();

        return redirect()->route('fiscalYearQuarter.index')->with('success', 'Fiscal year and quarter deleted successfully.');
    }
}