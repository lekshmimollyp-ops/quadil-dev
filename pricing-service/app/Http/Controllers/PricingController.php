<?php

namespace App\Http\Controllers;

use App\Models\PricingRule;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Set or Update Pricing Rules for a Tenant
     */
    public function setRules(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'base_fare' => 'required|numeric',
            'per_km_rate' => 'required|numeric',
            'per_kg_rate' => 'required|numeric',
        ]);

        $rule = PricingRule::updateOrCreate(
            ['tenant_id' => $request->tenant_id],
            [
                'base_fare' => $request->base_fare,
                'per_km_rate' => $request->per_km_rate,
                'per_kg_rate' => $request->per_kg_rate,
            ]
        );

        return response()->json([
            'message' => 'Pricing rules updated successfully',
            'rule' => $rule
        ]);
    }

    /**
     * Calculate Fare based on distance and weight
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|uuid',
            'distance_km' => 'required|numeric',
            'weight_kg' => 'required|numeric',
        ]);

        $rule = PricingRule::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->first();

        if (!$rule) {
            return response()->json(['message' => 'No pricing rules found for this tenant'], 404);
        }

        $base = (float) $rule->base_fare;
        $distanceCost = (float) $request->distance_km * (float) $rule->per_km_rate;
        $weightCost = (float) $request->weight_kg * (float) $rule->per_kg_rate;

        $total = $base + $distanceCost + $weightCost;

        return response()->json([
            'tenant_id' => $request->tenant_id,
            'calculation' => [
                'base_fare' => $base,
                'distance_cost' => $distanceCost,
                'weight_cost' => $weightCost,
            ],
            'total_fare' => round($total, 2)
        ]);
    }
}
