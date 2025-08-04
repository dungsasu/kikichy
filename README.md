# laravel cms

## Hướng dẫn sử dụng nhanh

1. Khởi động dịch vụ bằng Docker Compose:
    ```bash
    docker-compose up -d
    ```

2. Truy cập vào container:
    ```bash
    docker-compose exec app bash
    ```

3. Cài đặt các package:
    ```bash
    composer install
    yarn
    ```

4. Theo dõi thay đổi CSS/JS với Laravel Mix:
    ```bash
    yarn watch
    ```

5. Truy cập ứng dụng tại địa chỉ bạn đã cấu hình (thường là `http://localhost:8080`).

---
