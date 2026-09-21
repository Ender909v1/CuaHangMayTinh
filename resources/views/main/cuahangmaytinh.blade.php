<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>CuaHangMayTinh — Máy tính & Linh kiện</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
  --bg:#EFEFEA; --panel:#FFFFFF; --ink:#14171F; --muted:#5B6270;
  --primary:#2B3A67; --primary-2:#1D2A4D; --accent:#E8A33D; --line:#D8D8D0;
  box-sizing:border-box;
  padding-top:env(safe-area-inset-top,0px); padding-bottom:env(safe-area-inset-bottom,0px);
}
*{box-sizing:inherit}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
body{margin:0;background:var(--bg);color:var(--ink);font-family:'Inter',sans-serif;font-size:16px;line-height:1.55}
h1,h2,h3,.brand{font-family:'Space Grotesk',sans-serif}
.wrap{max-width:1140px;margin:0 auto;padding:0 20px}
a{text-decoration:none;color:inherit}

/* nav */
.topbar{position:sticky;top:env(safe-area-inset-top,0px);z-index:10;background:var(--bg);border-bottom:1px solid var(--line)}
.topbar .wrap{display:flex;align-items:center;justify-content:space-between;height:68px}
.brand{font-weight:700;font-size:1.3rem;letter-spacing:-0.01em}
.brand span{color:var(--accent)}
.navlinks{display:flex;gap:28px;font-size:.95rem;font-weight:500}
.navlinks a:hover{color:var(--primary)}
.btn-order{background:var(--primary);color:#fff;padding:9px 18px;border-radius:6px;font-weight:600;font-size:.9rem}
.btn-order:hover{background:var(--primary-2);color:#fff}
.navtoggle{display:none}

/* hero */
.hero{padding:64px 0 56px;border-bottom:1px solid var(--line)}
.hero .wrap{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:center}
.hero h1{font-size:2.6rem;line-height:1.15;font-weight:700;margin:0 0 18px;max-width:14ch}
.hero p{color:var(--muted);max-width:46ch;margin:0 0 26px;font-size:1.05rem}
.hero .cta{display:flex;gap:14px}
.btn-primary2{background:var(--accent);color:var(--ink);padding:13px 24px;border-radius:6px;font-weight:600}
.btn-primary2:hover{background:#d6912f;color:var(--ink)}
.btn-outline2{border:1px solid var(--ink);padding:13px 24px;border-radius:6px;font-weight:600}
.btn-outline2:hover{background:var(--ink);color:#fff}
.stats{display:flex;gap:32px;margin-top:36px}
.stats div b{display:block;font-size:1.5rem;font-family:'Space Grotesk',sans-serif}
.stats div span{color:var(--muted);font-size:.85rem}

/* board graphic */
.board{aspect-ratio:1/1;background:var(--primary-2);border-radius:14px;padding:20px;display:grid;grid-template-columns:repeat(5,1fr);grid-template-rows:repeat(5,1fr);gap:10px;position:relative;overflow:hidden}
.board::after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(232,163,61,.18),transparent 60%)}
.board i{background:rgba(255,255,255,.08);border-radius:4px}
.board i:nth-child(3n){background:rgba(232,163,61,.55)}
.board i:nth-child(7),.board i:nth-child(13),.board i:nth-child(19){grid-column:span 2;grid-row:span 2;background:rgba(255,255,255,.14);border:1px solid rgba(232,163,61,.4)}

/* categories */
.section{padding:64px 0}
.section h2{font-size:1.7rem;margin:0 0 8px}
.section .lead{color:var(--muted);margin:0 0 32px;max-width:52ch}
.cats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.cat{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:22px;text-align:left}
.cat .ico{width:38px;height:38px;border-radius:8px;background:var(--primary);margin-bottom:14px;position:relative}
.cat .ico::before{content:"";position:absolute;inset:10px;border:2px solid var(--accent);border-radius:3px}
.cat h3{font-size:1.02rem;margin:0 0 4px}
.cat p{color:var(--muted);font-size:.88rem;margin:0}

/* products */
.products{background:var(--primary-2)}
.products h2,.products .lead{color:#fff}
.products .lead{color:#B9C0D4}
.pgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.pcard{background:#fff;border-radius:10px;overflow:hidden;display:flex;flex-direction:column}
.pcard .thumb{height:140px;background:linear-gradient(135deg,var(--primary),#3d5590);display:flex;align-items:center;justify-content:center}
.pcard .thumb svg{width:52px;height:52px;stroke:var(--accent);fill:none;stroke-width:1.6}
.pcard .body{padding:18px}
.pcard h3{font-size:1rem;margin:0 0 6px}
.pcard .spec{color:var(--muted);font-size:.85rem;margin:0 0 14px}
.pcard .row{display:flex;align-items:center;justify-content:space-between}
.pcard .price{font-weight:700;font-family:'Space Grotesk',sans-serif}
.pcard .buy{font-size:.85rem;font-weight:600;color:var(--primary);border:1px solid var(--line);padding:7px 12px;border-radius:6px}
.pcard .buy:hover{background:var(--primary);color:#fff;border-color:var(--primary)}

/* why us */
.why .wrap{display:grid;grid-template-columns:1fr 1fr;gap:40px}
.why ul{list-style:none;margin:0;padding:0;display:grid;gap:22px}
.why li{display:flex;gap:14px;align-items:flex-start;border-top:1px solid var(--line);padding-top:18px}
.why li:first-child{border-top:none;padding-top:0}
.why b{display:block;margin-bottom:3px}
.why span{color:var(--muted);font-size:.9rem}
.why .dot{width:8px;height:8px;border-radius:50%;background:var(--accent);margin-top:8px;flex-shrink:0}

/* footer */
footer{border-top:1px solid var(--line);padding:40px 0}
footer .wrap{display:flex;justify-content:space-between;flex-wrap:wrap;gap:20px}
footer .col b{display:block;margin-bottom:8px}
footer .col p,footer .col a{display:block;color:var(--muted);font-size:.9rem;margin:2px 0}
.copyright{text-align:center;color:var(--muted);font-size:.82rem;margin-top:28px}

@media (max-width:860px){
  .navlinks{display:none}
  .hero .wrap{grid-template-columns:1fr}
  .board{max-width:340px;margin:0 auto}
  .cats{grid-template-columns:repeat(2,1fr)}
  .pgrid{grid-template-columns:1fr}
  .why .wrap{grid-template-columns:1fr}
  .hero h1{font-size:2rem}
}
</style>
</head>
<body>

<header class="topbar">
  <div class="wrap">
    <a class="brand" href="#">CuaHang<span>MayTinh</span></a>
    <nav class="navlinks">
      <a href="#cats">Sản phẩm</a>
      <a href="#products">Nổi bật</a>
      <a href="#why">Vì sao chọn</a>
      <a href="#lienhe">Liên hệ</a>
    </nav>
    <a class="btn-order" href="#lienhe">Đặt hàng ngay</a>
  </div>
</header>

<section class="hero">
  <div class="wrap">
    <div>
      <h1>Build cấu hình, chọn đúng linh kiện, không lo bảo hành.</h1>
      <p>CuaHangMayTinh chuyên laptop, PC gaming, linh kiện và phụ kiện chính hãng — tư vấn cấu hình theo nhu cầu và ngân sách thực tế của bạn.</p>
      <div class="cta">
        <a class="btn-primary2" href="#products">Xem sản phẩm</a>
        <a class="btn-outline2" href="#lienhe">Tư vấn cấu hình</a>
      </div>
      <div class="stats">
        <div><b>12 năm</b><span>kinh nghiệm lắp ráp</span></div>
        <div><b>4.800+</b><span>máy đã bàn giao</span></div>
        <div><b>24 tháng</b><span>bảo hành chính hãng</span></div>
      </div>
    </div>
    <div class="board" aria-hidden="true">
      <i></i><i></i><i></i><i></i><i></i>
      <i></i><i></i><i></i><i></i><i></i>
      <i></i><i></i><i></i><i></i><i></i>
      <i></i><i></i><i></i><i></i><i></i>
      <i></i><i></i><i></i><i></i><i></i>
    </div>
  </div>
</section>

<section class="section" id="cats">
  <div class="wrap">
    <h2>Danh mục chính</h2>
    <p class="lead">Từ máy dựng sẵn đến từng con ốc linh kiện, đủ cho người mới lẫn dân build PC lâu năm.</p>
    <div class="cats">
      <div class="cat"><div class="ico"></div><h3>Laptop</h3><p>Văn phòng, đồ họa, gaming</p></div>
      <div class="cat"><div class="ico"></div><h3>PC / Máy bộ</h3><p>Lắp sẵn hoặc theo yêu cầu</p></div>
      <div class="cat"><div class="ico"></div><h3>Linh kiện</h3><p>CPU, VGA, RAM, mainboard</p></div>
      <div class="cat"><div class="ico"></div><h3>Phụ kiện</h3><p>Màn hình, bàn phím, chuột</p></div>
    </div>
  </div>
</section>

<section class="section products" id="products">
  <div class="wrap">
    <h2>Sản phẩm nổi bật</h2>
    <p class="lead">Vài lựa chọn đang bán chạy nhất tuần này.</p>
    <div class="pgrid">
      <div class="pcard">
        <div class="thumb"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="1"/><path d="M8 20h8M12 16v4"/></svg></div>
        <div class="body">
          <h3>PC Gaming Ryzen 5 / RTX 4060</h3>
          <p class="spec">16GB RAM · SSD 512GB · Case kính cường lực</p>
          <div class="row"><span class="price">22.900.000₫</span><a class="buy" href="#lienhe">Xem chi tiết</a></div>
        </div>
      </div>
      <div class="pcard">
        <div class="thumb"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="12" rx="1"/><path d="M2 20h20"/></svg></div>
        <div class="body">
          <h3>Laptop văn phòng Core i5</h3>
          <p class="spec">8GB RAM · SSD 256GB · 15.6" FHD</p>
          <div class="row"><span class="price">13.500.000₫</span><a class="buy" href="#lienhe">Xem chi tiết</a></div>
        </div>
      </div>
      <div class="pcard">
        <div class="thumb"><svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M9 9h6v6H9z"/></svg></div>
        <div class="body">
          <h3>Card đồ họa RTX 4070 Super</h3>
          <p class="spec">12GB GDDR6X · Tản nhiệt 3 quạt</p>
          <div class="row"><span class="price">15.200.000₫</span><a class="buy" href="#lienhe">Xem chi tiết</a></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section why" id="why">
  <div class="wrap">
    <div>
      <h2>Vì sao chọn CuaHangMayTinh</h2>
      <p class="lead">Không chỉ bán máy — hỗ trợ suốt vòng đời sản phẩm.</p>
    </div>
    <ul>
      <li><span class="dot"></span><div><b>Bảo hành chính hãng 24 tháng</b><span>Đổi mới trong 7 ngày nếu lỗi do nhà sản xuất</span></div></li>
      <li><span class="dot"></span><div><b>Tư vấn cấu hình miễn phí</b><span>Kỹ thuật viên tư vấn theo đúng nhu cầu và ngân sách</span></div></li>
      <li><span class="dot"></span><div><b>Giao hàng trong ngày</b><span>Nội thành trong 4 giờ, toàn quốc 2–3 ngày</span></div></li>
      <li><span class="dot"></span><div><b>Trả góp 0% lãi suất</b><span>Qua thẻ tín dụng hoặc công ty tài chính liên kết</span></div></li>
    </ul>
  </div>
</section>

<footer id="lienhe">
  <div class="wrap">
    <div class="col">
      <b>CuaHangMayTinh</b>
      <p>123 Đường Nguyễn Văn Cừ, Quận 5, TP. Hồ Chí Minh</p>
      <p>Mở cửa 8:00 – 21:00, T2 – CN</p>
    </div>
    <div class="col">
      <b>Liên hệ</b>
      <a href="tel:0901234567">090 123 4567</a>
      <a href="mailto:[email protected]">[email protected]</a>
    </div>
    <div class="col">
      <b>Chính sách</b>
      <a href="#">Bảo hành</a>
      <a href="#">Đổi trả</a>
      <a href="#">Vận chuyển</a>
    </div>
  </div>
  <p class="copyright">© 2026 CuaHangMayTinh. Bản quyền thuộc về cửa hàng.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
