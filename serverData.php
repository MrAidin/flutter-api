<?php
class ServerData
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function new_product($data)
    {
        $new_product = $this->connection->prepare("SELECT * FROM products ORDER BY id DESC limit 10");
        $new_product->execute();
        $new_product = $new_product->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($new_product);
    }
    public function order_product($data)
    {
        $order_product = $this->connection->prepare("SELECT * FROM products ORDER BY order_number DESC limit 10");
        $order_product->execute();
        $order_product = $order_product->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($order_product);
    }
    public function get_sliders($data)
    {
        $sliders = $this->connection->prepare("SELECT * FROM sliders ORDER BY id DESC limit 5");
        $sliders->execute();
        $sliders = $sliders->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($sliders);
    }
    public function getProductData($data)
    {

        $id = array_key_exists('id', $data) ? $data['id'] : 0;

        $product_data = $this->connection->prepare("SELECT * FROM products WHERE id=?");
        $product_data->execute([$id]);
        $product_data  = $product_data->fetch(PDO::FETCH_ASSOC);
        if ($product_data) {
            echo json_encode($product_data);
        } else {
            echo json_encode([]);
        }
    }
    public function add_comment($data)
    {
        $product_id = array_key_exists('product_id', $data) ? $data['product_id'] : 0;
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comment'])) {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $comment = $_POST['comment'];
            $time = time();
            $new_comment = $this->connection->prepare("INSERT INTO `comment` ( `name`, `email`, `content`, `product_id`, `parent_id`, `status`,`time`) VALUES ( ?, ?, ?, ?,0,1,?)");
            $new_comment->execute([$name, $email, $comment, $product_id, $time]);
            return "ثبت نظر با موفقیت انجام شد.";
        }
    }


    public function get_comment($data)
    {
        require_once 'jdf.php';
        $array = array();
        $page=array_key_exists('page',$data)?$data['page']:1;
        $product_id = array_key_exists('product_id', $data) ? $data['product_id'] : 0;
        $page=($page-1)*10;
        $comment = $this->connection->prepare("SELECT * FROM comment WHERE product_id=? ORDER BY id DESC limit $page,10");
        $comment->execute([$product_id]);
        $comment = $comment->fetchAll(PDO::FETCH_ASSOC);
        foreach ($comment as $key => $value) {

            $date = jdate('Y-m-d', $value['time']);
            $array[$key]['name'] = $value['name'];
            $array[$key]['content'] = $value['content'];
            $array[$key]['date'] = $date;
        }

        echo json_encode($array);

    }
}
