<?php
header('Content-Type: application/json');

// Daftar saran pencarian yang diurutkan sesuai abjad
$suggestions = [
    'Abu Dhabi',
    'Addis Ababa',
    'Algiers',
    'Amman',
    'Amsterdam',
    'Ankara',
    'Argentina',
    'Athens',
    'Auckland',
    'Baghdad',
    'Baku',
    'Bali',
    'Bangkok',
    'Beijing',
    'Brazil',
    'Budapest',
    'Buenos Aires',
    'Cairo',
    'Canada',
    'Cape Town',
    'Caracas',
    'Casablanca',
    'Chennai',
    'Colombo',
    'Copenhagen',
    'Dhaka',
    'Doha',
    'Dubai',
    'Dublin',
    'Egypt',
    'Finland',
    'France',
    'Germany',
    'Greece',
    'Hanoi',
    'Havana',
    'Helsinki',
    'India',
    'Italy',
    'Jakarta',
    'Jamaica',
    'Japan',
    'Johannesburg',
    'Kathmandu',
    'Kiev',
    'Kingston',
    'Korea',
    'Kuala Lumpur',
    'Kuwait',
    'Lagos',
    'Lima',
    'Lisbon',
    'London',
    'Madrid',
    'Medan',
    'Mexico City',
    'Moscow',
    'Mumbai',
    'Nairobi',
    'New York',
    'Osaka',
    'Paris',
    'Peru',
    'Rome',
    'Seoul',
    'Singapore',
    'Sydney',
    'Tokyo',
    'Toronto',
    'Venice',
    'Vienna',
    'Warsaw',
    'Zurich',
];

$query = $_GET['q'];

$result = array_filter($suggestions, function($suggestion) use ($query) {
    return stripos($suggestion, $query) !== false;
});

// Urutkan hasil pencarian dari A hingga Z
sort($result);

echo json_encode(['suggestions' => $result]);
?>
