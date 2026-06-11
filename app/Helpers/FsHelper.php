<?php

use Illuminate\Support\Str;

if (!function_exists('fsPost')) {
  function fsPost($key = null)
  {
    $request = request();
    if ($key == null) {
      $post = $request->post();
      unset($post['_token'], $post['_ajax_st']);
    } else {
      $post = $request->input($key);
    }
    return $post;
  }
}

if (!function_exists('fsGet')) {
  function fsGet($key)
  {
    $request = request();
    $get = $request->get($key);

    return $get;
  }
}

if (!function_exists('fsResponse')) {
  function fsResponse($code, $url, $d)
  {
    $status = false;
    $message = "";

    if ($code == '11') {
      $status = true;
      $message = 'Data berhasil dibuat';
    }

    if ($code == '12') {
      $status = true;
      $message = 'Data berhasil diubah';
    }

    return [
      'status' => $status,
      'code' => $code,
      'message' => $message,
      'url' => $url,
      'data' => $d,
    ];
  }
}

if (!function_exists('strReplaceBetween')) {
  function strReplaceBetween($str, $needle_start, $needle_end, $replacement)
  {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);

    $pos = strpos($str, $needle_end, $start);
    $end = $pos === false ? strlen($str) : $pos;

    return substr_replace($str, $replacement, $start, $end - $start);
  }
}

if (!function_exists('camelToSnake')) {
  function camelToSnake($string)
  {
    // Sisipkan underscore sebelum huruf kapital dan ubah semuanya menjadi huruf kecil
    $snake = preg_replace('/(?<!^)[A-Z]/', '_$0', $string);
    return strtolower($snake);
  }
}

if (!function_exists('fsFolderExists')) {
  function fsFolderExists($folder)
  {
    $path = realpath($folder);

    if ($path !== false && is_dir($path)) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('fsUploadFileBase64')) {
  function fsUploadFileBase64($field)
  {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
      if ($_FILES[$field]['name'] != '') {
        $tmp = $_FILES[$field]['tmp_name'];
        $type = $_FILES[$field]['type'];
        // filter hanya png, jpg, jpeg
        if (!in_array($type, ['image/png', 'image/jpeg'])) {
          return [
            'status' => false,
            'message' => 'Tipe file tidak di izinkan',
          ];
        } else {
          $data = file_get_contents($tmp);
          $base64 = 'data:' . $type . ';base64,' . base64_encode($data);
          return [
            'status' => true,
            'data' => [
              'base64' => $base64,
            ],
          ];
        }
      } else {
        return [
          'status' => false,
          'message' => 'File error',
        ];
      }
    } else {
      return [
        'status' => false,
        'message' => 'File error',
      ];
    }
  }
}

if (!function_exists('fsUploadFile')) {
  function fsUploadFile($folder, $field, $config = [])
  {
    $arr_folder = explode('/', $folder);
    $upload_path = public_path(env('UPLOAD_ROOT_FOLDER') . '/');

    if (count($arr_folder) > 0) {
      for ($i = 0; $i < count($arr_folder); $i++) {
        $upload_path .= $arr_folder[$i] . '/';
        if (!fsFolderExists($upload_path)) {
          mkdir($upload_path, 0755, true);
        }
      }
    } else {
      $upload_path .= $folder . '/';
      if (!fsFolderExists($upload_path)) {
        mkdir($upload_path, 0755, true);
      }
    }

    if (request()->hasFile($field)) {
      $file = request()->file($field);
      $extension = $file->getClientOriginalExtension();

      // Cek ekstensi file yang di izinkan
      if (isset($config['allowed_ext']) && is_array($config['allowed_ext'])) {
        if (!in_array(strtolower($extension), $config['allowed_ext'])) {
          return [
            'status' => false,
            'message' => 'Ekstensi file tidak di izinkan. Hanya ' . implode(', ', $config['allowed_ext']) . ' yang di izinkan.'
          ];
        }
      }

      // Cek ukuran maksimum (dalam kilobyte)
      if (isset($config['max_size']) && is_numeric($config['max_size'])) {
        $fileSizeKb = $file->getSize() / 1024; // Ukuran file dalam kilobyte
        if ($fileSizeKb > $config['max_size']) {
          return [
            'status' => false,
            'message' => 'Ukuran file melebihi batas maksimum ' . $config['max_size'] . ' KB.'
          ];
        }
      }

      // Generate nama file unik
      $fileName = $file->getClientOriginalName();
      $fileName = pathinfo($fileName, PATHINFO_FILENAME);
      $fileName = Str::slug($fileName);
      $fileName .= '.' . $extension;

      if (isset($config['specific_st']) && $config['specific_st'] == true) {
        $encryptedFilename = Str::uuid() . '.' . $extension;
        $fileName = $encryptedFilename;
      }

      $destinationPath = $upload_path;

      $moved = $file->move($destinationPath, $fileName);

      if ($moved) {
        return [
          'status' => true,
          'message' => 'File berhasil di upload.',
          'data' => [
            'file_name' => $fileName,
            'file_url' => env('UPLOAD_ROOT_FOLDER') . '/' . $folder . '/' . $fileName,
            'file_path' => $destinationPath . $fileName
          ]
        ];
      } else {
        return [
          'status' => false,
          'message' => 'Gagal memindahkan file ke folder tujuan.'
        ];
      }
    } else {
      return [
        'status' => false,
        'message' => 'Tidak ada file yang di upload.'
      ];
    }
  }
}

if (!function_exists('fsEncrypt')) {
  function fsEncrypt($value)
  {
    $key = hash('sha256', env('ENCRYPTION_KEY'), true);
    $encrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
    return $encrypter->encryptString($value);
  }
}

if (!function_exists('fsDecrypt')) {
  function fsDecrypt($value)
  {
    try {
      $key = hash('sha256', env('ENCRYPTION_KEY'), true);
      $encrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
      return $encrypter->decryptString($value);
    } catch (\Exception $e) {
      return '';
    }
  }
}

if (!function_exists('generateId')) {
  function generateId($table, $column, $length = 12)
  {
    $lastId = \Illuminate\Support\Facades\DB::table($table)
      ->max($column);
    
    if ($lastId) {
      $nextId = (int) $lastId + 1;
    } else {
      $nextId = 1;
    }
    
    return str_pad($nextId, $length, '0', STR_PAD_LEFT);
  }
}

if (!function_exists('csrfValidate')) {
  function csrfValidate($token)
  {
    return hash_equals(csrf_token(), $token);
  }
}
