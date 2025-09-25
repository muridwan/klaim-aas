<?php

use App\Models\User;
use Illuminate\Support\Str;

function pretty($params)
{
  return print("<pre>" . print_r($params, true) . "</pre>");
}

function responseToString($json)
{
  return json_decode($json);
}


function get_data_user()
{
  $user = User::with('main_position')->where('uuid', session('user_uuid'))->first();
  return $user;
}

function countDataByLevel($data)
{
  // Konversi ke Collection jika berbentuk array
  if (is_array($data)) {
    $data = collect($data);
  }

  $counts = [];

  // Fungsi rekursif untuk menghitung data berdasarkan level
  $traverse = function ($node) use (&$counts, &$traverse) {
    $level = $node->get('level');

    // Tambahkan ke jumlah level
    if (!isset($counts[$level])) {
      $counts[$level] = 0;
    }
    $counts[$level]++;

    // Iterasi ke childs
    if ($node->has('childs') && is_iterable($node->get('childs'))) {
      collect($node->get('childs'))->each(function ($child) use (&$traverse) {
        $traverse(collect($child));
      });
    }
  };

  // Mulai rekursi
  $traverse($data);

  return $counts;
}

function get_person_name($inputString)
{
  $position = strpos($inputString, 'QQ');

  if ($position !== false) {
    return trim(substr($inputString, $position + strlen('QQ')));
  }

  return '';
}

function after_qq($text)
{
  return ucwords(strtolower(trim(Str::after($text, 'QQ'))));
}

function nama_kantor($text)
{
  return trim(Str::after($text, 'Syariah'));
}

function get_words($text, $length = 1)
{
  $words = explode(' ', trim($text)); // Memisahkan kata berdasarkan spasi
  $firstThree = array_slice($words, 0, $length); // Mengambil 3 kata pertama
  return implode(' ', $firstThree); // Menggabungkan kembali menjadi string
}
