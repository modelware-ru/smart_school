<?php
const DATA_DIR = "data/";
const HASH_DIR = "hash/";
const HASH_EXT = ".md5";
const VERSION_FILE_NAME = "version.txt";

function process_template($template_name)
{
    mb_http_output("UTF-8");
    ob_start("mb_output_handler");
//    ob_start();
    require "$template_name";
    $res = ob_get_contents();
    ob_end_clean();
    return $res;
}

function update_version_file($output_dir)
{
    $path_to_version_file = $output_dir . VERSION_FILE_NAME;
    $version = 1;
    if (file_exists($path_to_version_file)) {
        $version = intval(file_get_contents($path_to_version_file));
        $version++;
    }
    file_put_contents($path_to_version_file, $version);
    return $version;
}

function get_version_value($output_dir)
{
    $path_to_version_file = $output_dir . VERSION_FILE_NAME;
    if (file_exists($path_to_version_file)) {
        return intval(file_get_contents($path_to_version_file));
    } else {
        return update_version_file($output_dir);
    }
}

// Проверить hash, если hash нет или не равны - создать файл и создать новый hash. Если hash есть и равны, то ничего не делать
// Параметры:
// $output_dir - выходная директория для файла
// $output_sub_dir - выходная поддиректория для файла
// $file_name - название файла
// $data - данные файла
function create_file($output_dir, $output_sub_dir, $file_name, $data)
{
    $path_to_hash_file_dir = $output_dir . HASH_DIR . $output_sub_dir;
    $path_to_file_dir = $output_dir . DATA_DIR . $output_sub_dir;

    if (!file_exists($path_to_hash_file_dir)) {
        mkdir($path_to_hash_file_dir, 0777, true);
    }

    if (!file_exists($path_to_file_dir)) {
        mkdir($path_to_file_dir, 0777, true);
    }

    $version = get_version_value($output_dir);

    $file_data_hash = md5($data);

    $path_to_hash_file = $path_to_hash_file_dir . $file_name . ".md5";
    $path_to_file = $path_to_file_dir . $file_name;

    // check hash
    if (file_exists($path_to_hash_file)) {
        $existed_file_data_hash_all_data = file_get_contents($path_to_hash_file);
        $existed_file_data_hash_all_lines = explode(PHP_EOL, $existed_file_data_hash_all_data);
        $version_hash = intval($existed_file_data_hash_all_lines[0]);
        $existed_file_data_hash = $existed_file_data_hash_all_lines[1];

        if ($existed_file_data_hash != $file_data_hash) {
            file_put_contents($path_to_hash_file, $version . PHP_EOL . $file_data_hash);
            file_put_contents($path_to_file, $data);
        } else { // hashes are equal
            if (file_exists($path_to_file)) {
                unlink($path_to_file);
            }
            file_put_contents($path_to_hash_file, $version . PHP_EOL . $file_data_hash);
        }
    } else // no hash
    {
        file_put_contents($path_to_file, $data);
        file_put_contents($path_to_hash_file, $version . PHP_EOL . $file_data_hash);
    }
}