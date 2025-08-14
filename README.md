# real-estate-api
đây là phần back end của dự án, phần này thực ra em triển khai bằng laragon nên rất lẹ, nhược điểm hình như laragon chỉ có cho window, các bước như bên dưới:

## Backend (Laravel API) — Chạy trên Laragon

### Bước 1: Cài đặt Laragon
- Tải Laragon: https://laragon.org/download/
- Cài đặt Laragon (bản **Full**) để có sẵn PHP, MySQL, Composer.
- Khi cài đặt xong, có thể chọn option ngôn ngữ là tiếng việt
- Mở Laragon, bấm **Chạy** để chạy Apache/Nginx và MySQL.

> Lưu ý: Laragon mặc định MySQL user là `root`, mật khẩu rỗng (`""`). và toàn bộ các lệnh nếu có gõ tay ở terminal không được để bộ gõ chữ tiếng việt

---

### Bước 2: Clone source và cài đặt package
- Mở terminal trong laragon, sau đó ta nhập lệnh như bên dưới để vào thư mục www của laragon(nếu đã ở thư mục này vui lòng bỏ qua dòng cd <nơi lưu trữ của bạn>\laragon\www), như ví dụ dưới, em để cài laragon vào ổ C
```bash
cd C:\laragon\www
git clone https://github.com/nguyuenvuyp/real-estate-api.git
cd real-estate-api
composer install
```

### Bước 3: Tạo database
- Chạy lần lượt các lệnh dưới đây:
```bash
mysql -u root -p
CREATE DATABASE real_estate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```
> Lưu ý: ở câu lệnh mysql -u root -p, khi chạy xong có yêu cầu điền password, có thể để trống, nếu bạn điền password, thì cũng điền vào DB_PASSWORD trong .env, và khi tạo db real_estate, có thể nhận được thông báo đã tồn tại, lúc này có thể xóa db cũ có trùng tên đi và chạy lại câu lệnh này(mở laragon, vào "Cơ sở dữ liệu", chọn vào Laragon.MySQL, lúc này sẽ có danh sách db xổ ra, chuột phải vào db cần xóa và chọn drop database)

### Bước 4: Cấu hình .env
- Sao chép file bằng lệnh:
```bash
cp .env.example .env
```
hoặc tìm đến file .env.example copy thủ công nội dung sang file .env
> Trong file .env, app url đang có giá trị APP_URL=http://localhost, nếu chạy local thì ok, nếu k hãy sửa chỗ này cho khớp

- Dù đã copy env, nhưng hãy chú ý check lại các tham số quan trọng (mặc định khi tạo .enx thì các trường này bị comment bằng dấu "#") dưới đây, tránh để chúng bị comment lại:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=real_estate
DB_USERNAME=root
DB_PASSWORD=
```
 

### Bước 5: Khởi tạo ứng dụng
- Sử dụng lần lượt các lệnh dưới đây để khởi tạo
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### Bước 6: Chạy API
```bash
php artisan serve
```
