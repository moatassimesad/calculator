<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    // Returns the calculator view (Web Route)
    public function index(): View
    {
        return view('calculator');
    }

    // Calculates the operations (API Endpoint)
    public function calculate(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'previousValue' => 'required|numeric',
            'currentValue' => 'required|numeric',
            'operation' => 'required|in:+,-,x,/',
        ]);

        $previousValue = floatval($validatedData['previousValue']);
        $currentValue = floatval($validatedData['currentValue']);
        $operator = $validatedData['operation'];

        switch ($operator) {
            case '+':
                $result = $previousValue + $currentValue;
                break;
            case '-':
                $result = $previousValue - $currentValue;
                break;
            case 'x':
                $result = $previousValue * $currentValue;
                break;
            case '/':
                if ($currentValue === 0.0) {
                    return response()->json(['success' => false, 'error' => 'Cannot divide by zero!']);
                }
                $result = $previousValue / $currentValue;
                break;
            default:
                return response()->json(['success' => false, 'error' => 'Invalid operation']);
        }

        return response()->json(['success' => true, 'result' => $result]);
    }
}
