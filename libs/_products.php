<!-- start product -->
<?php
// Lấy ID Sản phẩm từ tham số URL
$id = $_GET['id'] ?? 1; 
//Duyệt qua dữ liệu sản phẩm và Hiển thị chi tiết sản phẩm
foreach ($product->getData() as $item):
    if ($item['id'] == $id):
        ?>
        <section id="product" class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <!--Hiển thị Hình ảnh, Nút Mua hàng và Nút Thêm vào giỏ hàng -->
                        <img src="<?php echo $item['image']; ?>" alt="product" class="img-fluid">
                        <div class="pt-4 font-size-16">
                            <div class="col">
                                <button type="submit" class="btn btn-success form-control"
                                    onclick="alert('This is demo only')">Tiến hành mua hàng</button>
                            </div>
                            <div class="col">
                                <form method="POST">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $_COOKIE['user_id'] ?? 0 ?>">
                                    <?php
                                    // Nếu sản phẩm đã có trong giỏ hàng, disable nút Thêm vào giỏ hàng thành In the Cart
                                    if (in_array($item['id'], $cart->getCartId($cart->getCart($_COOKIE['user_id'] ?? 0)) ?? [])) {
                                        echo '<button type="submit" disabled class="btn btn-success form-control">In the Cart</button>';
                                    } else {
                                        echo '<button type="submit" name="buy_product_submit" class="btn btn-warning form-control">Thêm vào giỏ hàng</button>';
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 py-5">
                        <h5 class="font-size-20">
                            <?php echo $item['name']; ?>
                        </h5>
                        <small>by
                            <?php echo $manage->getBrand($item['brand'])['brand']; ?>
                        </small>
                        <div class="d-flex">
                            <div class="rating text-warning font-size-12">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="far fa-star"></i></span>
                            </div>
                            <a href="#" class="px-2 font-size-14">20,534 ratings | 1000+ answered questions</a>
                        </div>
                        <hr class="m-0">

                        <!--- Gía sản phẩm -->
                        <table class="my-3 font-size-14">
                            <tr>
                                <td>M.R.P:</td>
                                <td><strike>$162.00</strike></td>
                            </tr>
                            <tr>
                                <td>Deal Price:</td>
                                <td class="font-size-20 text-danger">$
                                    <span>
                                        <?php echo $item['price'] ?? 0; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="font-size-12">&nbsp;&nbsp;Inclusive of all taxes</span>
                                </td>
                            </tr>
                            <tr>
                                <td>You Save:</td>
                                <td><span class="font-size-16 text-danger">$152.00</span></td>
                            </tr>
                        </table>
                        <!-- !Gía sản phẩm-->

                        <!-- #Chính sách -->
                        <div id="policy">
                            <div class="d-flex">
                                <div class="return text-center me-5">
                                    <div class="font-size-20 my-2 color-second">
                                        <span class="fas fa-retweet border p-3 rounded-pill"></span>
                                    </div>
                                    <a href="#" class="font-size-12">10 Days <br> Replacement</a>
                                </div>
                                <div class="return text-center me-5">
                                    <div class="font-size-20 my-2 color-second">
                                        <span class="fas fa-truck  border p-3 rounded-pill"></span>
                                    </div>
                                    <a href="#" class="font-size-12">About <br>Our</a>
                                </div>
                                <div class="return text-center me-5">
                                    <div class="font-size-20 my-2 color-second">
                                        <span class="fas fa-check-double border p-3 rounded-pill"></span>
                                    </div>
                                    <a href="#" class="font-size-12">1 Year <br>Warranty</a>
                                </div>
                            </div>
                        </div>
                        <!-- !Chính sách -->
                        <hr>
                        <!-- order-details -->
                        <div id="order-details" class="d-flex flex-column">
                            <small>Delivery by : Mar 29 - Apr 1</small>
                            <small>Sold by <a href="#">Daily Electronics </a>(4.5 out of 5 | 18,198 ratings)</small>
                            <small><i class="fas fa-map-marker-alt color-primary"></i>&nbsp;&nbsp;Deliver to Customer -
                                424201</small>
                        </div>
                        <!-- !order-details -->
                        <div class="row align-items-center">
                            <div class="col-6">
                                <!-- color -->
                                <div class="color my-3">
                                    <div class="d-flex justify-content-between">
                                        <h6>Color:</h6>
                                        <div class="p-2 color-yellow-bg rounded-circle">
                                            <button class="btn font-size-14"></button>
                                        </div>
                                        <div class="p-2 color-primary-bg rounded-circle">
                                            <button class="btn font-size-14"></button>
                                        </div>
                                        <div class="p-2 color-second-bg rounded-circle">
                                            <button class="btn font-size-14"></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- !color -->
                            </div>
                            <div class="col-6">
                                <!-- product qty section -->
                                <div class="qty d-flex">
                                    <h6>Quantity</h6>
                                    <div class="px-4 d-flex">
                                        <button class="qty-up border bg-light w-25" data-id="pro1"><i
                                                class="fas fa-angle-up"></i></button>
                                        <input type="text" data-id="pro1"
                                            class="qty_input text-center border px-2 w-50" disabled value="1"
                                            placeholder="1">
                                        <button data-id="pro1" class="qty-down border bg-light w-25"><i
                                                class="fas fa-angle-down"></i></button>
                                    </div>
                                </div>
                                <!-- !product qty section -->
                            </div>
                        </div>
                        <!-- size -->
                        <div class="size my-3">
                            <h6>Size :</h6>
                            <div class="d-flex justify-content-between w-75">
                                <div class="border p-2">
                                    <button type="button" class="btn p-0 font-size-14">4GB RAM</button>
                                </div>
                                <div class="border p-2">
                                    <button type="button" class="btn p-0 font-size-14">6GB RAM</button>
                                </div>
                                <div class="border p-2">
                                    <button type="button" class="btn p-0 font-size-14">8GB RAM</button>
                                </div>
                            </div>
                        </div>
                        <!-- !size -->
                    </div>
                    <div class="col-12">
                        <h6>Mô tả sản phẩm</h6>
                        <hr>
                        <p class="font-size-14">iPhone XS Max là một chiếc smartphone cao cấp của Apple, mang lại trải nghiệm đỉnh cao với thiết kế đẹp mắt,
                             hiệu suất mạnh mẽ và nhiều tính năng tiên tiến. Với màn hình lớn, camera chất lượng cao và khả năng xử lý nhanh chóng, nó là sự lựa chọn 
                             hoàn hảo cho những người đang tìm kiếm một chiếc điện thoại thông minh đỉnh cao.
                        </p>
                        <p class="font-size-14">Màn hình Super Retina:
iPhone XS Max trang bị một màn hình Super Retina OLED kích thước lớn, mang lại chất lượng hình ảnh sắc nét, màu sắc chân thực và độ tương phản ấn tượng. Điều này tạo ra trải nghiệm xem video, chơi game và duyệt web đỉnh cao.

Camera Dual 12MP:
Hệ thống camera kép 12MP ở phía sau của iPhone XS Max cung cấp khả năng chụp ảnh và quay video chất lượng cao. Chức năng chụp ảnh xóa phông và khả năng quay video 4K làm nổi bật khả năng sáng tạo của người dùng.

Hiệu suất mạnh mẽ:
Được trang bị chip A12 Bionic, iPhone XS Max cung cấp hiệu suất xử lý nhanh chóng, hiệu quả năng lượng và khả năng xử lý trí tuệ nhân tạo. Điều này giúp nâng cao trải nghiệm người dùng trong mọi tình huống sử dụng.

Hệ điều hành iOS:
iPhone XS Max chạy trên hệ điều hành iOS, đảm bảo sự tương thích tốt nhất với các ứng dụng và dịch vụ của Apple. Người dùng có quyền trải nghiệm các tính năng mới nhất và được hỗ trợ bảo mật liên tục.

Thiết kế đẳng cấp:
Với khung kim loại chất lượng cao và mặt kính cường lực, iPhone XS Max không chỉ đẹp mắt mà còn chắc chắn và bền bỉ. Thiết kế bezel-less và khe loa stereo cung cấp trải nghiệm nghe gọi và giải trí đỉnh cao.

Khả năng chống nước và bụi:
iPhone XS Max được chứng nhận chống nước và bụi theo tiêu chuẩn IP68, bảo vệ điện thoại khỏi nước, bụi và các tác động từ môi trường xung quanh.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- !start product -->
        <?php
    endif;
endforeach;
?>