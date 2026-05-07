<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Crop;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        Crop::truncate();

        $crops = [
            // Grains & Cereals (Anaaj)
            'Wheat',        // Gehun
            'Rice',         // Chawal
            'Corn / Maize', // Makka
            'Barley',       // Jau
            'Millet',       // Bajra
            'Sorghum',      // Jowar
            'Oats',         // Jai
            'Rye',

            // Pulses & Legumes (Dalhaan)
            'Soybean',      // Soya
            'Chickpea',     // Chana
            'Lentil',       // Masoor Dal
            'Pigeon Pea',   // Arhar / Tur Dal
            'Black Gram',   // Urad Dal
            'Green Gram',   // Moong Dal
            'Kidney Beans', // Rajma
            'Peas',         // Matar

            // Vegetables (Sabziyan)
            'Tomato',
            'Potato',
            'Onion',        // Pyaz
            'Garlic',       // Lahsun
            'Cauliflower',  // Phool Gobhi
            'Cabbage',      // Patta Gobhi
            'Brinjal',      // Baingan
            'Okra',         // Bhindi
            'Spinach',      // Palak
            'Pumpkin',      // Kaddu
            'Bottle Gourd', // Lauki
            'Bitter Gourd', // Karela
            'Ridge Gourd',  // Tori
            'Carrot',       // Gajar
            'Radish',       // Mooli
            'Capsicum',     // Shimla Mirch
            'Cucumber',     // Kheera
            'Chilli',       // Mirch

            // Fruits (Phal)
            'Mango',        // Aam
            'Banana',       // Kela
            'Papaya',       // Papita
            'Guava',        // Amrood
            'Watermelon',   // Tarbooz
            'Muskmelon',    // Kharbooja
            'Pomegranate',  // Anar
            'Lemon',        // Nimbu
            'Orange',       // Santra
            'Grapes',       // Angoor
            'Apple',        // Seb
            'Strawberry',

            // Cash Crops (Nakdi Faslein)
            'Cotton',       // Kapas
            'Sugarcane',    // Ganna
            'Tobacco',      // Tambaku
            'Jute',         // Paat
            'Sunflower',    // Surajmukhi
            'Mustard',      // Sarson
            'Groundnut',    // Moongfali
            'Sesame',       // Til
            'Castor',       // Arandi
            'Flaxseed',     // Alsi

            // Spices (Masaale)
            'Turmeric',     // Haldi
            'Ginger',       // Adrak
            'Coriander',    // Dhaniya
            'Cumin',        // Jeera
            'Fenugreek',    // Methi
            'Black Pepper', // Kali Mirch
            'Cardamom',     // Elaichi
            'Clove',        // Laung
        ];

        foreach ($crops as $name) {
            Crop::create(['name' => $name]);
        }

        $this->command->info('[CropSeeder] ' . count($crops) . ' crops seeded.');
    }
}
