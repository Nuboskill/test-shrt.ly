<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shortener extends CI_Controller
{
    private $BASE62_ALPHABET = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('LinkModel');
    }

    public function index()
    {
        $data['csrf'] = [
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        ];
        $this->load->view('shortener_home', $data);
    }

    public function getShorten()
    {
        $_POST['link'] = strtolower(trim($this->addScheme($_POST['link'])));

        // Настройка валидации
        $this->setUpValidation();

        if ($this->form_validation->run() == false) {
            // Произошла ошибка при валидации
            $response = ['success' => false, 'message' => validation_errors()];
        } else {
            // Возвращаем укороченную ссылку
            $response = ['success' => true, 'link' => $this->makeShorten($_POST['link'])];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response + ['csrf' => $this->security->get_csrf_hash()]));
    }

    public function redirectToUrl($hash)
    {
        // Проверяем, что такой hash есть в базе
        $row = $this->LinkModel->searchByHash($hash, ['url']);
        if ($row) {
            redirect($row->url, 'location', 301);
        } else {
            show_404();
        }

    }

    private function addScheme($url, $scheme = 'http://')
    {
        if ($url) {
            return is_null(parse_url($url, PHP_URL_SCHEME)) ? $scheme . $url : $url;
        }

        return null;
    }

    private function makeShorten($url)
    {
        // Проверяем нет ли хеша для данного URL в базе
        $row = $this->LinkModel->searchByUrl($url, ['hash']);
        if ($row) {
            $hash = $row->hash;
        } else {
            // Генерируем хеш на основе идентификатора
            $id = $this->LinkModel->getNextId();
            $hash = $this->generateHashForId($id);

            // Вставляем запись в базу
            $data = ['hash' => $hash, 'url' => $url];
            $this->LinkModel->insertEntry($data);
        }

        return base_url().$hash;
    }

    private function generateHashForId($id) {
        $hash = '';
        $hashDigits = [];
        $dividend = (int) $id;

        while ($dividend > 0) {
            $remainder = floor($dividend % 62);
            $dividend = floor($dividend / 62);
            array_unshift($hashDigits, $remainder);
        }

        foreach ($hashDigits as $v) {
            $hash .= $this->BASE62_ALPHABET[$v];
        }

        // Если hash совпал с каким либо ключем $route
        if (in_array($hash, array_keys($this->router->routes))) {
            $hash .= '~';
        }

        return $hash;
    }

    private function setUpValidation()
    {
        $this->load->library('form_validation');

        // Rules
        $this->form_validation->set_rules('link', 'Link', 'trim|required|max_length[191]|valid_url|not_shortener');

        // Messages
        $this->form_validation->set_message('required', 'URL не передан.');
        $this->form_validation->set_message('max_length', 'Максимальная длина URL 191 символ.');
        $this->form_validation->set_message('valid_url', 'Передан не валидный URL.');
        $this->form_validation->set_message('not_shortener', 'Ссылка уже укорочена.');

        // Custom functions
        function not_shortener($url) {
            return !(strpos($url, base_url()) === 0);
        }
    }
}
