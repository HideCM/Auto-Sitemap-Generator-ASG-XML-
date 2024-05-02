![enter image description here](https://cdn.iconscout.com/icon/free/png-256/free-xml-file-2330558-1950399.png)
# Auto Sitemap Generator (ASG XML)

#### 1. MÔ TẢ CHUNG:

> ASG  là một công cụ mạnh mẽ được viết bằng PHP, cho phép tạo và quản
> lý các tệp sitemap XML dễ dàng và tự động. Nó hỗ trợ tạo sitemap từ dữ
> liệu cơ sở dữ liệu MySQL và tự động chia nhỏ thành các phần nhỏ để
> quản lý dễ dàng.

#### 2. CÁC CHỨC NĂNG CHÍNH:

-   **Tạo Sitemap Tự Động:** Script có khả năng tự động tạo sitemap XML từ cơ sở dữ liệu MySQL, giúp tiết kiệm thời gian và công sức của người dùng.
    
-   **Phân Chia Sitemap:** Script tự động phân chia sitemap thành các phần nhỏ (chunk) để tối ưu hóa hiệu suất và quản lý tệp tin.
    
-   **Loại Bỏ URL Trùng Lặp:** Script có chức năng loại bỏ các URL trùng lặp trong các chunk sitemap, đảm bảo tính duy nhất và hiệu quả của sitemap.
    
-   **Tạo Sitemap Index:** Script tự động tạo sitemap index để liên kết các chunk sitemap lại với nhau, giúp máy chủ tìm kiếm hiểu cấu trúc sitemap.
    

#### 3. ƯU ĐIỂM:

-   **Tự Động Hóa:** Script giúp tự động hóa quy trình tạo và quản lý sitemap, giảm thiểu công sức thủ công và giảm thiểu lỗi nhân factor.

-   **Big Size XML:** Giả sử bạn có một file XML quá lớn, và server của bạn không thể query đến database, thì ASG XML sẽ giúp bạn chia nhỏ mọi thứ.
    
-   **Quản Lý Linh Hoạt:** Script cho phép tùy chỉnh cấu hình một cách linh hoạt, bao gồm cấu hình cơ sở dữ liệu, cấu trúc sitemap, và tần suất cập nhật.
    
-   **Tối Ưu Hóa SEO:** Bằng cách tạo sitemap chất lượng và tối ưu hóa, script giúp cải thiện hiệu suất SEO và tăng khả năng tìm thấy trang web trên các công cụ tìm kiếm.
    
-   **Dễ Dàng Mở Rộng:** Script được viết bằng PHP, cho phép dễ dàng mở rộng và tùy chỉnh theo nhu cầu cụ thể của dự án.
-   **Phù Hợp Với Tất Cả Môi Trường:** Bạn không cần phải cài đặt một LIB bất kỳ nào của PHP, nó sử dụng chỉ với DB của bạn, miễn là bạn có quyền truy cập vào database. Phù hợp với tất cả các hệ điều hành như ubuntu, centos ... thậm chí là Xampp trên Windows.

#### 4. CÀI ĐẶT:
1. **Clone the Repository** hoặc tải zip về giải nén

    git clone https://github.com/HideCM/Auto-Sitemap-Generator-ASG-XML-.git
2. **Make and chmod 777 folder and files**

    mkdir sitemap (or whatever you want)

    sudo chmod -R 777 path/sitemap-folder
    
3. **Re-check all permissions can be read and write**
4. **Access to URL:** https://domain.com/sitemap-folder/sitemap.php?token=**yourtoken** (edit the token line number 6)
5. Sau khi chạy xong, kết quả sẽ được save và hoàn thành ở URL: https://domain.com/sitemap-folder/sitemap_index.xml. Bạn có thể cung cấp cho google để nhận biết Sitemap của bạn

#### 5. MỘT VÀI THÔNG SỐ LƯU Ý:
- **Token**: để ngăn chặn access vào link một cách bất hợp pháp. Edit ở Line 6
- **Database**: Line 9-12 các thông số cần thiết để kết nối vào database
- **$table_name**: Cấu hình table bạn muốn query vào
- **Line 18-20**: Một số column bạn có thể edit tùy thích
- **$base_url**: Cấu hình domain của bạn, ví dụ bạn có một site tin tức với format https://domain.com/tin-tuc/tieu-de thì hãy cấu hình https://domain.com/tin-tuc/ để nó nhận biết phân cách giữa các dữ liệu slug
- **$latest_100_rows_only**: Setting để kiểm soát xem có tạo sơ đồ trang web chỉ với 100 hàng mới nhất hay không (true = tạo sơ đồ trang web chỉ với 100 hàng mới nhất, false = tạo sơ đồ trang web đầy đủ). Mục đích sử dụng để khi khởi tạo lần đầu, bạn hãy set nó với giá trị là **false**, script sẽ thực hiện query toàn bộ row có trong table, column. Tiếp đến bạn tạo cronjob và set giá trị là **true** để hằng ngày, giờ, phút sẽ thực hiện nhập những row mới nhất.
- Replace all **sitemap_chunk_** thành một tên file khác nếu bạn không muốn file tạo thành có chử Chunk
- 
#### 6. SUPPORT:

> Telegram @kanypham
