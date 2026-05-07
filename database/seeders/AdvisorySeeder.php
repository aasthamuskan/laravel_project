<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Crop;
use App\Models\Advisory;

/**
 * AdvisorySeeder
 *
 * Populates the advisories table with expert-authored advice for:
 *  - 8 crops × 4 seasons × 5 weather conditions
 * (not every combination is seeded — only agronomically meaningful ones)
 *
 * Weather conditions (normalized): Clear, Cloudy, Rainy, Stormy, Cold
 * Seasons: Spring, Summer, Monsoon, Winter
 */
class AdvisorySeeder extends Seeder
{
    public function run(): void
    {
        Advisory::truncate();

        // Build a crop name → ID map for readable definitions below
        $crops = Crop::pluck('id', 'name');

        $advisories = [

            // ── WHEAT ──────────────────────────────────────────────────────
            ['crop' => 'Wheat', 'season' => 'Winter', 'condition' => 'Clear',
             'advice' => 'Clear winter skies are ideal for wheat growth. Apply potassic fertilizer and check for aphid colonies on the underside of leaves.'],

            ['crop' => 'Wheat', 'season' => 'Winter', 'condition' => 'Rainy',
             'advice' => 'Rain during winter wheat season — delay irrigation immediately. Ensure field channels are open to prevent waterlogging. Watch for yellow rust disease.'],

            ['crop' => 'Wheat', 'season' => 'Winter', 'condition' => 'Cold',
             'advice' => 'Frost risk is high. If temperature drops below 2°C, apply light protective irrigation ("frost irrigation") to release latent heat around roots.'],

            ['crop' => 'Wheat', 'season' => 'Spring', 'condition' => 'Clear',
             'advice' => 'Spring sunshine promotes tillering. This is the optimal window for foliar urea spray (2%) to boost grain filling.'],

            ['crop' => 'Wheat', 'season' => 'Spring', 'condition' => 'Cloudy',
             'advice' => 'Overcast spring weather increases humidity. Scout for powdery mildew and apply fungicide if coverage exceeds 5% of leaf area.'],

            ['crop' => 'Wheat', 'season' => 'Summer', 'condition' => 'Stormy',
             'advice' => 'Thunderstorms during harvest risk lodging (crop falling over). Harvest at the earliest possible window between storms.'],

            // ── RICE ───────────────────────────────────────────────────────
            ['crop' => 'Rice', 'season' => 'Monsoon', 'condition' => 'Rainy',
             'advice' => 'Heavy monsoon rain is favorable for transplanted rice. Maintain 5 cm standing water. Avoid nitrogen application immediately before rain to prevent leaching.'],

            ['crop' => 'Rice', 'season' => 'Monsoon', 'condition' => 'Stormy',
             'advice' => 'Cyclone / storm warning. Drain excess water from paddy to protect young plants from lodging. Re-flood after storm passes.'],

            ['crop' => 'Rice', 'season' => 'Monsoon', 'condition' => 'Clear',
             'advice' => 'Clear days during monsoon are ideal for pesticide application. Apply pre-emergent weedicide within 3 days of transplanting.'],

            ['crop' => 'Rice', 'season' => 'Summer', 'condition' => 'Clear',
             'advice' => 'Summer-sown rice (Boro) needs 6-8 irrigations. Schedule irrigations every 3 days. Apply basal NPK fertilizer before the first irrigation.'],

            ['crop' => 'Rice', 'season' => 'Winter', 'condition' => 'Cold',
             'advice' => 'Cold temperatures stunt rice growth. Use cold-tolerant varieties. Apply silica-based fertilizer to strengthen cell walls against cold damage.'],

            // ── CORN ───────────────────────────────────────────────────────
            ['crop' => 'Corn', 'season' => 'Summer', 'condition' => 'Clear',
             'advice' => 'Peak summer heat. Irrigation every 5-7 days is critical during silking stage. Do not skip irrigation — missing this window reduces yield by 20-50%.'],

            ['crop' => 'Corn', 'season' => 'Summer', 'condition' => 'Rainy',
             'advice' => 'Rainfall expected. Delay irrigation and check field drainage. Waterlogging for more than 48 hours at knee-high stage can cause significant yield loss.'],

            ['crop' => 'Corn', 'season' => 'Spring', 'condition' => 'Cloudy',
             'advice' => 'Overcast spring weather. Monitor for gray leaf spot and northern corn leaf blight. Apply statin fungicide if more than 3 leaves show symptoms.'],

            ['crop' => 'Corn', 'season' => 'Monsoon', 'condition' => 'Rainy',
             'advice' => 'Good rainfall for corn. Ensure zinc sulfate application — corn is highly zinc-sensitive. Apply 25 kg/ha zinc sulfate with the first top-dressing.'],

            // ── COTTON ─────────────────────────────────────────────────────
            ['crop' => 'Cotton', 'season' => 'Summer', 'condition' => 'Clear',
             'advice' => 'Hot dry weather suits cotton boll development. Apply potash at 60 kg/ha. Monitor for bollworm egg masses on leaf underside (> 1 egg/plant = spray threshold).'],

            ['crop' => 'Cotton', 'season' => 'Summer', 'condition' => 'Rainy',
             'advice' => 'Rain at boll-opening stage causes boll rot and staining. Harvest opened bolls immediately before rain begins. Apply mancozeb if grey mildew appears.'],

            ['crop' => 'Cotton', 'season' => 'Monsoon', 'condition' => 'Cloudy',
             'advice' => 'Humid overcast conditions favor sucking pests (jassids, thrips). Apply imidacloprid or lambda-cyhalothrin. Inspect 5 plants per field every 3 days.'],

            // ── SUGARCANE ──────────────────────────────────────────────────
            ['crop' => 'Sugarcane', 'season' => 'Summer', 'condition' => 'Clear',
             'advice' => 'High temperatures increase evapotranspiration. Irrigate every 7-10 days. Apply earthing-up to support stalk growth and prevent lodging.'],

            ['crop' => 'Sugarcane', 'season' => 'Monsoon', 'condition' => 'Rainy',
             'advice' => 'Waterlogging is the biggest risk. Ensure furrow drains are clear. Red rot disease spreads rapidly in wet conditions — remove infected stalks immediately.'],

            ['crop' => 'Sugarcane', 'season' => 'Winter', 'condition' => 'Cold',
             'advice' => 'Sucrose accumulates faster in cool weather — ideal pre-harvest period. Begin harvesting early in the season when juice Brix reaches 18-20°.'],

            // ── TOMATO ─────────────────────────────────────────────────────
            ['crop' => 'Tomato', 'season' => 'Spring', 'condition' => 'Clear',
             'advice' => 'Optimal conditions for tomato flowering. Apply calcium nitrate (5 g/L) to prevent blossom-end rot. Stake plants before they exceed 60 cm.'],

            ['crop' => 'Tomato', 'season' => 'Summer', 'condition' => 'Clear',
             'advice' => 'Drip irrigate every 2 days. Cover soil with mulch to retain moisture. Afternoon shade cloth (30%) can reduce heat stress on fruits above 35°C.'],

            ['crop' => 'Tomato', 'season' => 'Monsoon', 'condition' => 'Rainy',
             'advice' => 'Rain increases early blight and late blight risk. Apply mancozeb + cymoxanil every 7 days. Avoid overhead irrigation. Prune lower leaves touching soil.'],

            ['crop' => 'Tomato', 'season' => 'Winter', 'condition' => 'Cold',
             'advice' => 'Cold nights slow fruit set. Use polythene mulch and wind-breaks. Apply potassium-rich fertilizer to improve cold tolerance of cell membranes.'],

            // ── POTATO ─────────────────────────────────────────────────────
            ['crop' => 'Potato', 'season' => 'Winter', 'condition' => 'Clear',
             'advice' => 'Clear winter days support tuber bulking. Apply second dose of nitrogen (50 kg/ha urea) and hill-up soil around plants to prevent greening.'],

            ['crop' => 'Potato', 'season' => 'Winter', 'condition' => 'Cold',
             'advice' => 'Sub-zero temperatures can damage tubers in the ground. Apply light irrigation (frost protection) or cover with straw mulch if frost is predicted.'],

            ['crop' => 'Potato', 'season' => 'Spring', 'condition' => 'Rainy',
             'advice' => 'Late blight (Phytophthora infestans) spreads explosively in wet spring. Apply metalaxyl + mancozeb fungicide every 5-7 days during wet periods.'],

            // ── SOYBEAN ────────────────────────────────────────────────────
            ['crop' => 'Soybean', 'season' => 'Monsoon', 'condition' => 'Clear',
             'advice' => 'Clear skies between monsoon showers — apply rhizobium culture to fix atmospheric nitrogen. This can replace 40-50 kg of urea per hectare.'],

            ['crop' => 'Soybean', 'season' => 'Monsoon', 'condition' => 'Rainy',
             'advice' => 'Heavy rain period. Delay sowing if field is waterlogged. Soybean is extremely sensitive to waterlogging during germination (up to 48-hour loss threshold).'],

            ['crop' => 'Soybean', 'season' => 'Summer', 'condition' => 'Cloudy',
             'advice' => 'Overcast summer weather may reduce photosynthesis during pod-fill. Apply foliar phosphate (0.5%) to support grain development under low light.'],
        ];

        $count = 0;
        foreach ($advisories as $row) {
            if (!isset($crops[$row['crop']])) {
                $this->command->warn("Crop not found: {$row['crop']} — skipping.");
                continue;
            }

            Advisory::create([
                'crop_id'           => $crops[$row['crop']],
                'season'            => $row['season'],
                'weather_condition' => $row['condition'],
                'advice'            => $row['advice'],
            ]);
            $count++;
        }

        $this->command->info("[AdvisorySeeder] {$count} advisories seeded across 8 crops.");
    }
}
