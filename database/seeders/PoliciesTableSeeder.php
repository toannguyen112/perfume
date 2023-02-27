<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PoliciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('posts')->delete();

        \DB::table('posts')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'Chính sách đổi trả',
                'slug' => 'chinh-sach-doi-tra',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'Chính sách vận chuyển',
                'slug' => 'chinh-sach-van-chuyen',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'Hướng dẫn mua hàng và thanh toán',
                'slug' => 'huong-dan-mua-hang-va-thanh-toan',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'title' => 'Chính sách thanh toán',
                'slug' => 'chinh-sach-thanh-toan',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'title' => 'Chính sách bảo mật',
                'slug' => 'chinh-sach-bao-mat',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'title' => 'Điều khoản sử dụng',
                'slug' => 'dieu-khoan-su-dung',
                'content' => $this->setContent(),
                'type' => 'POLICY',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
        ));
    }

    private function setContent(){
        return '<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="page-title category-title">
            <h1 class="title-head"><a href="#">Hướng dẫn thanh toán khi mua hàng tại Picassopen.vn</a></h1>
        </div>
        <div class="content-page">
            <table border="0" cellpadding="1" cellspacing="1" style="width:1100px;">
            <tbody>
            <tr>
                <td>
                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Trân trọng cảm ơn Quý khách đã đặt mua bút tại website&nbsp;<strong>Picassopen.vn</strong>, để thuận lợi trong quá trình thanh toán. Quý khách có thể lựa chọn một trong số những hình thức thanh toán dưới đây:</span></span></span></p>

                <p style="text-align: justify;">&nbsp;</p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1. Thanh toán trực tiếp tại Showroom</strong></span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Quý khách có nhu cầu đến xem, lựa chọn và mua bút cao cấp trực tiếp tại Showroom của bút ký cao cấp Hà Nội, vui lòng thanh toán trực tiếp bằng tiền mặt thông qua nhân viên bán hàng tại Showroom của chúng tôi.</span></span></span></p>

                <p style="text-align: justify;">&nbsp;</p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2. Giao hàng thu tiền tại nhà.</strong></span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Với Khách hàng khu vực Nội thành TP Hà Nội, Bút ký cao cấp Hà Nội giao hàng và thu tiền tại nhà cho khách hàng. Ngay khi kiểm tra và nhận bút, Quý khách vui lòng thanh toán trực tiếp cho nhân viên giao hàng. Bút ký cao cấp miễn phí toàn bộ chi phí giao hàng với các đơn hàng trên 500.000đ, với các đơn hàng nhỏ hơn, tùy giá trị và vị trí địa lý chúng tôi cộng thêm một phần chi phí giao hàng (Phí Ship). Phí Ship sẽ được nhân viên tư vấn thông báo cho Quý khách trước khi giao hàng.</span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Với Khách hàng Ngoại thành Hà Nội, nếu có yêu cầu giao hàng tại nhà, Bút ký cao cấp sẽ căn cứ vào vị trí địa lý, giá trị đơn hàng để tính chi phí giao hàng (Phí Ship). Phí Ship sẽ được nhân viên tư vấn thông báo cho Quý khách trước khi giao hàng.</span></span></span></p>

                <p style="text-align: justify;">&nbsp;</p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;3. Giao hàng thanh toán qua bưu điện.</strong></span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Với những khách hàng thuộc các tỉnh ở xa Hà Nội, Công ty sẽ ủy quyền cho Bưu điện chuyển phát và thu tiền tại nhà. Ngay khi nhận bút, Quý khách vui lòng thanh toán tiền trực tiếp cho nhân viên giao hàng của bên chuyển phát.</span></span></span></p>

                <p style="text-align: justify;">&nbsp;</p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;4. Thanh toán qua ngân hàng.</strong></span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; Quý khách có thể thanh toán bằng hình thức chuyển khoản qua tài khoản Ngân hàng. Ngay sau khi nhận được thông báo từ Ngân hàng về khoản thanh toán của Quý khách. Chúng tôi sẽ thực hiện đơn hàng, chuyển hàng theo đúng yêu cầu của Quý khách.</span></span></span></p>

                <p style="text-align: justify;">&nbsp;</p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;5. Hình thức khác:</strong></span></span></span></p>

                <p style="text-align: justify;"><span style="color:#000000;"><span style="font-size:14px;"><span style="font-family:Tahoma,Geneva,sans-serif;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Ngoài các hình thức thanh toán trên Công ty chúng tôi chấp nhận các hình thức thanh toán khác do Khách hàng đề nghị nếu thuận lợi cho Quý khách và đảm bảo quyền lợi và nguyên tắc thanh toán của Công ty.</span></span></span></p>
                </td>
                <td style="text-align: center; width: 350px;"><img src="//bizweb.dktcdn.net/100/117/539/files/hinh-thuc-thanh-toan-5f1704af-f3de-47da-9a17-3b0cacef2e59.jpg?v=1491213525576"></td>
            </tr>
            </tbody>
            </table>

            <p style="text-align: justify;">&nbsp;</p>
        </div>
    </div>';
    }
}
