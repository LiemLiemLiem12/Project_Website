/**
 * Policy.js - JavaScript functionality for the policy pages
 * Merged from both Layout and root JS files
 */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Active link highlighting
    const policyLinks = document.querySelectorAll('.policy-nav li a');
    const currentPath = window.location.pathname;
    
    policyLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (currentPath.includes(linkPath) || (currentPath.endsWith('/') && linkPath === 'index.html')) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }
    });
    
    // Add animation effect on scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.policy-section, .policy-notes, .policy-payment');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Set initial styles for animation
    document.querySelectorAll('.policy-section, .policy-notes, .policy-payment').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
    
    // Run on load and scroll
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
    
    // Mobile navigation toggle (for smaller screens)
    const setupMobileNav = () => {
        const sidebarTitle = document.querySelector('.sidebar-title');
        const policyNav = document.querySelector('.policy-nav');
        
        if (window.innerWidth < 768 && sidebarTitle && policyNav) {
            sidebarTitle.style.cursor = 'pointer';
            
            sidebarTitle.addEventListener('click', () => {
                if (policyNav.style.display === 'none' || getComputedStyle(policyNav).display === 'none') {
                    policyNav.style.display = 'block';
                    sidebarTitle.innerHTML = 'CHÍNH SÁCH <i class="fas fa-chevron-up"></i>';
                } else {
                    policyNav.style.display = 'none';
                    sidebarTitle.innerHTML = 'CHÍNH SÁCH <i class="fas fa-chevron-down"></i>';
                }
            });
            
            // Initial state
            if (window.innerWidth < 768) {
                policyNav.style.display = 'none';
                sidebarTitle.innerHTML = 'CHÍNH SÁCH <i class="fas fa-chevron-down"></i>';
            }
        }
    };
    
    // Check window size on load and resize
    setupMobileNav();
    window.addEventListener('resize', setupMobileNav);
}); 

////////////////////////////////////////
$(document).ready(function() {
    // Lắng nghe sự kiện click trên các liên kết trong sidebar
    $('.policy-nav li a').on('click', function(e) {
        e.preventDefault();
        
        // Lấy đường dẫn của ảnh từ thẻ img trong thẻ a được click
        var policyImg = $(this).find('img').attr('src');
        
        // Lấy text của chính sách
        var policyTitle = $(this).find('span').text();
        
        // Đổi ảnh ở phần content
        $('.policy-image img').attr('src', policyImg);
        
        // Nếu là chính sách khác nhau, cập nhật nội dung tương ứng
        if(policyTitle === "Chính sách đổi trả") {
            updateContentForPolicy("doitra");
        } else if(policyTitle === "Chính sách bảo mật") {
            updateContentForPolicy("baomat");
        } else if(policyTitle === "Chính sách giao hàng") {
            updateContentForPolicy("giaohang");
        } else if(policyTitle === "Khách hàng thân thiết") {
            updateContentForPolicy("thanthiet");
        } else if(policyTitle === "Ưu đãi sinh nhật") {
            updateContentForPolicy("sinhnhat");
        }
        
        // Cập nhật trạng thái active cho sidebar
        $('.policy-nav li').removeClass('active');
        $(this).parent().addClass('active');
        
        // Cập nhật tiêu đề trang
        $('.policy-title h1').text(policyTitle.toUpperCase());

        // Cập nhật URL và tiêu đề
        var urlParam = convertToSlug(policyTitle);
        history.pushState(null, null, '?policy=' + urlParam);
        document.title = policyTitle + ' - RSStore';
        
        // Scroll to top
        $('html, body').animate({scrollTop: 0}, 300);
    });
    
    // Hàm chuyển đổi từ tiếng Việt sang slug URL
    function convertToSlug(text) {
        return text
            .toLowerCase()
            .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, "a")
            .replace(/[èéẹẻẽêềếệểễ]/g, "e")
            .replace(/[ìíịỉĩ]/g, "i")
            .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, "o")
            .replace(/[ùúụủũưừứựửữ]/g, "u")
            .replace(/[ỳýỵỷỹ]/g, "y")
            .replace(/đ/g, "d")
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");
    }
    
    // Hàm cập nhật nội dung dựa trên loại chính sách
    function updateContentForPolicy(policyType) {
        let contentHtml = '';
        
        switch(policyType) {
            case "doitra":
                // Giữ nguyên nội dung hiện tại
                break;
                
            case "baomat":
                contentHtml = `
                <div class="policy-section">
                    <h2>1. CHÍNH SÁCH BẢO MẬT THÔNG TIN</h2>
                    <div class="policy-detail">
                        <p>RSStore cam kết bảo mật thông tin cá nhân của khách hàng và không chia sẻ với bất kỳ bên thứ ba nào ngoại trừ những trường hợp được quy định trong chính sách này hoặc khi có yêu cầu từ cơ quan chức năng có thẩm quyền.</p>
                        <h3>1.1. Thông tin RSStore thu thập</h3>
                        <ul>
                            <li>Thông tin cá nhân: Họ tên, số điện thoại, email, địa chỉ giao hàng, ngày sinh.</li>
                            <li>Thông tin thanh toán: Mã đơn hàng, lịch sử mua hàng.</li>
                            <li>Thông tin thiết bị: Loại thiết bị, phiên bản trình duyệt, địa chỉ IP.</li>
                        </ul>
                    </div>
                    
                    <div class="policy-detail">
                        <h3>1.2. Mục đích thu thập thông tin</h3>
                        <ul>
                            <li>Xử lý đơn hàng và giao sản phẩm.</li>
                            <li>Thông báo tình trạng đơn hàng, các chương trình khuyến mãi.</li>
                            <li>Giải quyết khiếu nại, hỗ trợ khách hàng.</li>
                            <li>Nghiên cứu thị trường và cải thiện dịch vụ.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>2. BẢO MẬT THÔNG TIN THANH TOÁN</h2>
                    <div class="policy-detail">
                        <p>RSStore cam kết bảo mật thông tin thanh toán của khách hàng bằng các biện pháp sau:</p>
                        <ul>
                            <li>Sử dụng công nghệ mã hóa SSL để bảo vệ thông tin.</li>
                            <li>Không lưu trữ thông tin thẻ tín dụng/ghi nợ của khách hàng.</li>
                            <li>Chỉ sử dụng các cổng thanh toán đáng tin cậy và được cấp phép.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>3. THỜI GIAN LƯU TRỮ THÔNG TIN</h2>
                    <div class="policy-detail">
                        <p>RSStore sẽ lưu trữ thông tin của khách hàng trong thời gian cần thiết để phục vụ các mục đích nêu trên và tuân thủ các quy định pháp luật về thời gian lưu trữ dữ liệu.</p>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>4. QUYỀN CỦA KHÁCH HÀNG</h2>
                    <div class="policy-detail">
                        <p>Khách hàng có quyền:</p>
                        <ul>
                            <li>Truy cập, chỉnh sửa thông tin cá nhân đã cung cấp.</li>
                            <li>Yêu cầu xóa thông tin cá nhân khỏi cơ sở dữ liệu.</li>
                            <li>Từ chối nhận email quảng cáo, khuyến mãi.</li>
                            <li>Khiếu nại về việc sử dụng thông tin cá nhân không đúng mục đích.</li>
                        </ul>
                    </div>
                </div>`;
                break;
                
            case "giaohang":
                contentHtml = `
                <div class="policy-section">
                    <h2>1. PHẠM VI GIAO HÀNG</h2>
                    <div class="policy-detail">
                        <p>RSStore giao hàng trên toàn quốc. Tuy nhiên, thời gian giao hàng có thể khác nhau tùy theo khu vực.</p>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>2. THỜI GIAN GIAO HÀNG</h2>
                    <div class="policy-detail">
                        <h3>2.1. Nội thành Hồ Chí Minh, Hà Nội và các thành phố lớn</h3>
                        <ul>
                            <li>Giao hàng trong 1-2 ngày làm việc (không tính thứ 7, Chủ Nhật và các ngày lễ).</li>
                        </ul>
                        
                        <h3>2.2. Các tỉnh thành khác</h3>
                        <ul>
                            <li>Giao hàng trong 3-5 ngày làm việc (không tính thứ 7, Chủ Nhật và các ngày lễ).</li>
                        </ul>
                        
                        <h3>2.3. Vùng sâu, vùng xa</h3>
                        <ul>
                            <li>Giao hàng trong 5-7 ngày làm việc (không tính thứ 7, Chủ Nhật và các ngày lễ).</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>3. PHÍ GIAO HÀNG</h2>
                    <div class="policy-detail">
                        <h3>3.1. Nội thành Hồ Chí Minh và Hà Nội</h3>
                        <ul>
                            <li>Phí giao hàng: 20.000đ cho đơn hàng dưới 300.000đ.</li>
                            <li>Miễn phí giao hàng cho đơn hàng từ 300.000đ trở lên.</li>
                        </ul>
                        
                        <h3>3.2. Các tỉnh thành khác</h3>
                        <ul>
                            <li>Phí giao hàng: 30.000đ cho đơn hàng dưới 500.000đ.</li>
                            <li>Miễn phí giao hàng cho đơn hàng từ 500.000đ trở lên.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>4. HÌNH THỨC GIAO HÀNG</h2>
                    <div class="policy-detail">
                        <p>RSStore hợp tác với các đơn vị vận chuyển uy tín để đảm bảo hàng hóa được giao đến khách hàng an toàn và đúng hẹn:</p>
                        <ul>
                            <li>Giao hàng tiêu chuẩn: Giao trong khung giờ hành chính.</li>
                            <li>Giao hàng nhanh: Áp dụng cho khu vực nội thành với phụ phí.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>5. THEO DÕI ĐƠN HÀNG</h2>
                    <div class="policy-detail">
                        <p>Khách hàng có thể theo dõi tình trạng đơn hàng bằng cách:</p>
                        <ul>
                            <li>Đăng nhập vào tài khoản trên website RSStore.</li>
                            <li>Gọi điện đến hotline 1900 633 349.</li>
                            <li>Truy cập đường link theo dõi đơn hàng được gửi qua SMS/Email.</li>
                        </ul>
                    </div>
                </div>`;
                break;
                
            case "thanthiet":
                contentHtml = `
                <div class="policy-section">
                    <h2>1. CHƯƠNG TRÌNH KHÁCH HÀNG THÂN THIẾT</h2>
                    <div class="policy-detail">
                        <p>Chương trình Khách hàng Thân thiết của RSStore được thiết kế để tưởng thưởng cho những khách hàng trung thành và thường xuyên mua sắm tại RSStore.</p>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>2. CÁC HẠNG THÀNH VIÊN</h2>
                    <div class="policy-detail">
                        <h3>2.1. Thành viên Bạc</h3>
                        <ul>
                            <li>Điều kiện: Tổng giá trị mua hàng từ 1.000.000đ đến dưới 3.000.000đ trong 12 tháng.</li>
                            <li>Ưu đãi: Giảm 5% cho mọi đơn hàng, tích lũy 5% giá trị đơn hàng vào điểm thưởng.</li>
                        </ul>
                        
                        <h3>2.2. Thành viên Vàng</h3>
                        <ul>
                            <li>Điều kiện: Tổng giá trị mua hàng từ 3.000.000đ đến dưới 5.000.000đ trong 12 tháng.</li>
                            <li>Ưu đãi: Giảm 7% cho mọi đơn hàng, tích lũy 7% giá trị đơn hàng vào điểm thưởng, quà sinh nhật.</li>
                        </ul>
                        
                        <h3>2.3. Thành viên Bạch kim</h3>
                        <ul>
                            <li>Điều kiện: Tổng giá trị mua hàng từ 5.000.000đ trở lên trong 12 tháng.</li>
                            <li>Ưu đãi: Giảm 10% cho mọi đơn hàng, tích lũy 10% giá trị đơn hàng vào điểm thưởng, quà sinh nhật, ưu tiên tham gia các sự kiện đặc biệt.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>3. ĐIỂM THƯỞNG</h2>
                    <div class="policy-detail">
                        <h3>3.1. Tích lũy điểm</h3>
                        <p>Khách hàng sẽ tích lũy điểm thưởng dựa trên giá trị đơn hàng sau khi trừ đi các khuyến mãi, giảm giá:</p>
                        <ul>
                            <li>1.000đ = 1 điểm</li>
                            <li>Điểm thưởng có hiệu lực trong vòng 12 tháng kể từ ngày tích lũy.</li>
                        </ul>
                        
                        <h3>3.2. Sử dụng điểm</h3>
                        <p>Khách hàng có thể đổi điểm thưởng để:</p>
                        <ul>
                            <li>Giảm giá trực tiếp trên đơn hàng: 100 điểm = 10.000đ.</li>
                            <li>Đổi lấy các sản phẩm/quà tặng đặc biệt.</li>
                            <li>Nâng cấp hạng thành viên nhanh hơn.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>4. ĐIỀU KIỆN SỬ DỤNG</h2>
                    <div class="policy-detail">
                        <ul>
                            <li>Chỉ áp dụng cho tài khoản cá nhân, không áp dụng cho tài khoản doanh nghiệp.</li>
                            <li>Khách hàng cần đăng nhập tài khoản khi mua hàng để tích lũy điểm.</li>
                            <li>Điểm thưởng và hạng thành viên sẽ được cập nhật trong vòng 24 giờ sau khi đơn hàng hoàn tất.</li>
                            <li>RSStore có quyền thay đổi chính sách khách hàng thân thiết sau khi thông báo trước 30 ngày.</li>
                        </ul>
                    </div>
                </div>`;
                break;
                
            case "sinhnhat":
                contentHtml = `
                <div class="policy-section">
                    <h2>1. CHƯƠNG TRÌNH ƯU ĐÃI SINH NHẬT</h2>
                    <div class="policy-detail">
                        <p>RSStore dành tặng những ưu đãi đặc biệt cho khách hàng trong tháng sinh nhật để cùng chúc mừng và tri ân sự ủng hộ của quý khách.</p>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>2. ĐỐI TƯỢNG ÁP DỤNG</h2>
                    <div class="policy-detail">
                        <p>Tất cả khách hàng đã đăng ký tài khoản và cung cấp đầy đủ thông tin ngày sinh trên hệ thống của RSStore.</p>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>3. THỜI GIAN ÁP DỤNG</h2>
                    <div class="policy-detail">
                        <p>Ưu đãi sinh nhật có hiệu lực trong tháng sinh nhật của khách hàng, cụ thể là:</p>
                        <ul>
                            <li>Bắt đầu từ ngày 01 của tháng sinh nhật.</li>
                            <li>Kết thúc vào ngày cuối cùng của tháng sinh nhật.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>4. ƯU ĐÃI SINH NHẬT</h2>
                    <div class="policy-detail">
                        <h3>4.1. Voucher giảm giá</h3>
                        <ul>
                            <li>Tặng voucher giảm 15% (tối đa 150.000đ) cho một đơn hàng bất kỳ trong tháng sinh nhật.</li>
                            <li>Mã voucher sẽ được gửi qua email và SMS vào ngày đầu tiên của tháng sinh nhật.</li>
                        </ul>
                        
                        <h3>4.2. Quà tặng sinh nhật</h3>
                        <ul>
                            <li>Thành viên Bạc: Phiếu quà tặng 100.000đ.</li>
                            <li>Thành viên Vàng: Phiếu quà tặng 200.000đ.</li>
                            <li>Thành viên Bạch kim: Phiếu quà tặng 300.000đ.</li>
                        </ul>
                        
                        <h3>4.3. Tích điểm đặc biệt</h3>
                        <ul>
                            <li>Nhận x2 điểm thưởng cho mọi đơn hàng trong tháng sinh nhật.</li>
                        </ul>
                        
                        <h3>4.4. Quà tặng kèm</h3>
                        <ul>
                            <li>Tặng một món quà phụ kiện nhỏ (vớ, khăn, phụ kiện thời trang) khi mua hàng tại cửa hàng trong ngày sinh nhật (khách hàng cần xuất trình CMND/CCCD).</li>
                        </ul>
                    </div>
                </div>
                
                <div class="policy-section">
                    <h2>5. ĐIỀU KIỆN ÁP DỤNG</h2>
                    <div class="policy-detail">
                        <ul>
                            <li>Mỗi khách hàng chỉ được sử dụng ưu đãi sinh nhật một lần trong tháng sinh nhật.</li>
                            <li>Quà tặng và voucher không có giá trị quy đổi thành tiền mặt.</li>
                            <li>Voucher sinh nhật không kết hợp với các chương trình khuyến mãi khác.</li>
                            <li>Khách hàng cần xuất trình giấy tờ tùy thân có thông tin ngày sinh khi sử dụng ưu đãi tại cửa hàng.</li>
                        </ul>
                    </div>
                </div>`;
                break;
                
            default:
                break;
        }
        
        // Cập nhật nội dung chính
        if(contentHtml) {
            $('.policy-content').find('.policy-section, .policy-notes, .policy-payment').remove();
            $('.policy-image').after(contentHtml);
        }
    }
    
    // Xử lý URL ban đầu khi tải trang
    function handleInitialUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const policyParam = urlParams.get('policy');
        
        if(policyParam) {
            // Tìm liên kết tương ứng trong sidebar
            let foundLink = false;
            $('.policy-nav li a').each(function() {
                const linkText = $(this).find('span').text();
                const linkSlug = convertToSlug(linkText);
                
                if(linkSlug === policyParam) {
                    $(this).trigger('click');
                    foundLink = true;
                    return false;
                }
            });
            
            // Nếu không tìm thấy liên kết phù hợp, mặc định hiển thị chính sách đổi trả
            if(!foundLink) {
                $('.policy-nav li:first-child a').trigger('click');
            }
        }
    }
    
    // Khởi tạo xử lý URL
    handleInitialUrl();
}); 