<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;
use App\Models\Exams\TotalProteinsAndFractions;
use Illuminate\Http\Request;
use Response;

class TotalProteinsAndFractionsController extends Controller
{
    public function __construct(private TotalProteinsAndFractionsServiceInterface $totalProteinsAndFractionsService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(QueryRequest $request): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }
}
