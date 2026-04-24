<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 60px 40px;
        color: white;
        margin-top: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .display-4 {
        font-weight: 700;
        letter-spacing: -1px;
    }

    .lead {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .btn-explore {
        background: #ffffff;
        color: #764ba2;
        font-weight: 600;
        border-radius: 30px;
        padding: 12px 30px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-explore:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        background: #f8f9fa;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="hero-section text-center text-md-start">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 mb-3">Welcome to Oursawithd Bookstore ✨📚</h1>
                        <p class="lead mb-4">
                            Temukan kisah, ilmu, dan imajinasi dalam koleksi buku terbaik kami. 
                            Jelajahi dunia baru dari setiap halaman yang kamu buka.
                        </p>
                        <button class="btn btn-explore">Mulai Belanja Sekarang</button>
                    </div>
                    <div class="col-md-4 d-none d-md-block text-center">
                        <span style="font-size: 120px;">📖</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>