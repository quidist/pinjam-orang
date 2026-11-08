import { useMemo, useState } from "react";

type IconName =
  | "arrow"
  | "calendar"
  | "check"
  | "chevron"
  | "clock"
  | "menu"
  | "people"
  | "search"
  | "shield"
  | "spark"
  | "star"
  | "x";

const paths: Record<IconName, React.ReactNode> = {
  arrow: <path d="M5 12h14m-5-5 5 5-5 5" />,
  calendar: (
    <>
      <rect x="3" y="5" width="18" height="16" rx="3" />
      <path d="M16 3v4M8 3v4M3 10h18" />
    </>
  ),
  check: <path d="m5 12 4 4L19 6" />,
  chevron: <path d="m9 18 6-6-6-6" />,
  clock: (
    <>
      <circle cx="12" cy="12" r="9" />
      <path d="M12 7v5l3 2" />
    </>
  ),
  menu: <path d="M4 7h16M4 12h16M4 17h16" />,
  people: (
    <>
      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
      <circle cx="9" cy="7" r="4" />
      <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
    </>
  ),
  search: (
    <>
      <circle cx="11" cy="11" r="7" />
      <path d="m20 20-4-4" />
    </>
  ),
  shield: (
    <>
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
      <path d="m9 12 2 2 4-4" />
    </>
  ),
  spark: (
    <path d="m12 3 1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3ZM5 15l.8 2.2L8 18l-2.2.8L5 21l-.8-2.2L2 18l2.2-.8L5 15Z" />
  ),
  star: (
    <path d="m12 3 2.75 5.57 6.15.9-4.45 4.33 1.05 6.12L12 17.03l-5.5 2.89 1.05-6.12L3.1 9.47l6.15-.9L12 3Z" />
  ),
  x: <path d="m6 6 12 12M18 6 6 18" />,
};

function Icon({
  name,
  size = 18,
  filled = false,
}: {
  name: IconName;
  size?: number;
  filled?: boolean;
}) {
  return (
    <svg
      aria-hidden="true"
      className="shrink-0"
      fill={filled ? "currentColor" : "none"}
      height={size}
      stroke="currentColor"
      strokeLinecap="round"
      strokeLinejoin="round"
      strokeWidth="1.8"
      viewBox="0 0 24 24"
      width={size}
    >
      {paths[name]}
    </svg>
  );
}

type Listing = {
  id: number;
  type: "ONLINE" | "OFFLINE";
  free: boolean;
  price: string;
  title: string;
  description: string;
  host: string;
  score: number;
  date: string;
  time: string;
  slots: number;
  city: string;
  image: string;
};

const listings: Listing[] = [
  {
    id: 1,
    type: "ONLINE",
    free: true,
    price: "GRATIS",
    title: "Bedah portofolio UI/UX tanpa basa-basi",
    description:
      "Bawa satu studi kasusmu. Kita bahas alur berpikir, tulisan, dan bagian yang masih bisa dibuat lebih tajam.",
    host: "Adit Rachman",
    score: 98,
    date: "Sabtu, 14 Nov",
    time: "14:00–15:30",
    slots: 2,
    city: "Virtual",
    image:
      "https://images.unsplash.com/photo-1526218769555-7a8cced31a78?crop=faces&fit=crop&w=160&h=160&q=85",
  },
  {
    id: 2,
    type: "OFFLINE",
    free: true,
    price: "GRATIS",
    title: "Jalan pagi sambil cerita di GBK",
    description:
      "Jalan santai dua putaran. Cocok buat kamu yang ingin ngobrol ringan, tanpa harus punya topik khusus.",
    host: "Nadya Putri",
    score: 100,
    date: "Minggu, 15 Nov",
    time: "06:30–08:00",
    slots: 3,
    city: "Jakarta",
    image:
      "https://images.unsplash.com/photo-1541537806773-cd2c2a215cc5?crop=faces&fit=crop&w=160&h=160&q=85",
  },
  {
    id: 3,
    type: "ONLINE",
    free: false,
    price: "Rp 35.000",
    title: "Ngobrol transisi karier ke dunia product",
    description:
      "Sesi jujur tentang pindah jalur, menyusun ulang CV, dan mencari peran pertama tanpa koneksi orang dalam.",
    host: "Raka Mahendra",
    score: 94,
    date: "Selasa, 17 Nov",
    time: "19:30–20:30",
    slots: 1,
    city: "Virtual",
    image:
      "https://images.unsplash.com/photo-1516011362164-3095a82b0af9?crop=faces&fit=crop&w=160&h=160&q=85",
  },
  {
    id: 4,
    type: "OFFLINE",
    free: false,
    price: "Rp 25.000",
    title: "Belajar meracik kopi manual brew dari nol",
    description:
      "Kenalan dengan V60 dan biji lokal. Biaya sudah termasuk kopi, kamu cukup datang dengan rasa ingin tahu.",
    host: "Tania Wibowo",
    score: 96,
    date: "Sabtu, 21 Nov",
    time: "10:00–11:30",
    slots: 2,
    city: "Bandung",
    image:
      "https://images.unsplash.com/photo-1523151401668-fff9e38a48e4?crop=faces&fit=crop&w=160&h=160&q=85",
  },
  {
    id: 5,
    type: "ONLINE",
    free: true,
    price: "GRATIS",
    title: "Teman belajar bahasa Inggris, no judging",
    description:
      "Latihan percakapan untuk pemula. Salah grammar tidak apa-apa—yang penting berani mulai bicara.",
    host: "Yusuf Alamsyah",
    score: 91,
    date: "Rabu, 18 Nov",
    time: "20:00–21:00",
    slots: 4,
    city: "Virtual",
    image:
      "https://images.unsplash.com/photo-1729695632364-e057cdf7eb2f?crop=faces&fit=crop&w=160&h=160&q=85",
  },
  {
    id: 6,
    type: "OFFLINE",
    free: true,
    price: "GRATIS",
    title: "Membaca sunyi bareng di taman kota",
    description:
      "Bawa buku apa saja. Kita baca satu jam, lalu saling cerita bagian yang paling mengena.",
    host: "Saras Dewi",
    score: 99,
    date: "Minggu, 22 Nov",
    time: "15:30–17:00",
    slots: 2,
    city: "Yogyakarta",
    image:
      "https://images.unsplash.com/photo-1593519544785-fd359b7068f1?crop=faces&fit=crop&w=160&h=160&q=85",
  },
];

function Button({
  children,
  className = "",
  variant = "primary",
  onClick,
  type = "button",
}: {
  children: React.ReactNode;
  className?: string;
  variant?: "primary" | "outline" | "quiet" | "dark";
  onClick?: () => void;
  type?: "button" | "submit";
}) {
  return (
    <button className={`button button-${variant} ${className}`} onClick={onClick} type={type}>
      {children}
    </button>
  );
}

function TrustBadge({ score }: { score: number }) {
  return (
    <span className="trust-badge">
      <Icon filled name="star" size={12} />
      {score} dipercaya
    </span>
  );
}

function ListingCard({ listing }: { listing: Listing }) {
  return (
    <article className="listing-card">
      <div className="card-accent" />
      <div className="card-topline">
        <div className="badge-row">
          <span className={`session-badge ${listing.type.toLowerCase()}`}>
            <span className="status-dot" />
            {listing.type}
          </span>
          <span className="city-label">{listing.city}</span>
        </div>
        <span className={listing.free ? "price free" : "price"}>{listing.price}</span>
      </div>

      <div className="host-row">
        <img alt={`Foto ${listing.host}`} className="avatar" src={listing.image} />
        <div>
          <div className="host-name">
            {listing.host}
            <span className="verified" title="Identitas terverifikasi">
              <Icon name="check" size={11} />
            </span>
          </div>
          <TrustBadge score={listing.score} />
        </div>
      </div>

      <div className="card-copy">
        <h3>{listing.title}</h3>
        <p>{listing.description}</p>
      </div>

      <div className="schedule">
        <div>
          <Icon name="calendar" size={16} />
          {listing.date}
        </div>
        <div>
          <Icon name="clock" size={16} />
          {listing.time} · 90 menit
        </div>
      </div>

      <div className="card-footer">
        <div className="slots">
          <span>
            <Icon name="people" size={16} /> Sisa {listing.slots} slot
          </span>
          <div className="slot-bar">
            <span style={{ width: `${100 - listing.slots * 12}%` }} />
          </div>
        </div>
        <Button className="detail-button" variant="outline">
          Lihat detail <Icon name="arrow" size={17} />
        </Button>
      </div>
    </article>
  );
}

export default function App() {
  const [query, setQuery] = useState("");
  const [type, setType] = useState("Semua sesi");
  const [freeOnly, setFreeOnly] = useState(false);
  const [applied, setApplied] = useState({ query: "", type: "Semua sesi", freeOnly: false });
  const [menuOpen, setMenuOpen] = useState(false);

  const filtered = useMemo(
    () =>
      listings.filter((item) => {
        const text = `${item.title} ${item.description} ${item.host} ${item.city}`.toLowerCase();
        return (
          text.includes(applied.query.toLowerCase()) &&
          (applied.type === "Semua sesi" || item.type === applied.type) &&
          (!applied.freeOnly || item.free)
        );
      }),
    [applied],
  );

  const applyFilters = () => setApplied({ query, type, freeOnly });

  return (
    <div className="app-shell">
      <header className="navbar">
        <div className="nav-inner">
          <a aria-label="PINJAMORANG beranda" className="brand" href="#">
            <span className="brand-mark">
              <span>P</span>
            </span>
            <span className="brand-copy">
              <strong>PINJAMORANG</strong>
              <small>Borrow a human. Return with a story.</small>
            </span>
          </a>
          <nav className="desktop-nav" aria-label="Navigasi utama">
            <a className="active" href="#katalog">
              Jelajahi katalog
            </a>
            <a href="#cara-kerja">Cara kerjanya</a>
            <a href="#keamanan">Keamanan</a>
          </nav>
          <div className="desktop-actions">
            <a className="login-link" href="#masuk">
              Masuk
            </a>
            <Button>
              Daftar sekarang <Icon name="arrow" size={16} />
            </Button>
          </div>
          <Button className="menu-button" onClick={() => setMenuOpen(!menuOpen)} variant="quiet">
            <Icon name={menuOpen ? "x" : "menu"} size={22} />
          </Button>
        </div>
        {menuOpen && (
          <div className="mobile-nav">
            <a href="#katalog">Jelajahi katalog</a>
            <a href="#cara-kerja">Cara kerjanya</a>
            <a href="#keamanan">Keamanan</a>
            <Button>Daftar sekarang</Button>
          </div>
        )}
      </header>

      <main>
        <section className="hero">
          <div className="hero-grid">
            <div className="hero-copy">
              <div className="eyebrow">
                <Icon name="spark" size={15} /> Waktu yang dibagi, cerita yang kembali
              </div>
              <h1>
                Temukan seseorang
                <br />
                untuk <em>berbagi waktu.</em>
              </h1>
              <p>
                Belajar hal baru, ngobrol tanpa dihakimi, atau sekadar punya teman jalan. Semua
                dimulai dari satu jam yang tulus.
              </p>
              <div className="hero-actions">
                <Button
                  onClick={() =>
                    document.querySelector("#katalog")?.scrollIntoView({ behavior: "smooth" })
                  }
                >
                  Mulai menjelajah <Icon name="arrow" />
                </Button>
                <div className="community-proof">
                  <div className="proof-avatars">
                    {listings.slice(0, 3).map((item) => (
                      <img alt="" key={item.id} src={item.image} />
                    ))}
                  </div>
                  <span>
                    <strong>1.200+ cerita</strong>
                    <br />
                    dimulai di sini
                  </span>
                </div>
              </div>
            </div>
            <div className="hero-visual" aria-label="Ilustrasi sesi berbagi waktu">
              <div className="photo-frame">
                <img
                  alt="Sekelompok teman berbincang santai di luar ruangan"
                  src="https://images.unsplash.com/photo-1523151401668-fff9e38a48e4?crop=entropy&fit=crop&w=900&h=720&q=88"
                />
                <div className="photo-label">Cerita baru, Minggu sore</div>
              </div>
              <div className="floating-note trust-note">
                <span className="note-icon">
                  <Icon name="shield" size={18} />
                </span>
                <span>
                  <strong>Ruang aman</strong>
                  <small>Identitas & batasan terverifikasi</small>
                </span>
              </div>
              <div className="floating-note quote-note">
                <span className="quote-mark">“</span>
                <span>Rasanya seperti ngobrol dengan teman lama.</span>
              </div>
              <svg aria-hidden="true" className="hero-scribble" viewBox="0 0 120 70">
                <path d="M4 64c38-4 78-23 106-58M90 7l20-1-3 19" />
              </svg>
            </div>
          </div>
        </section>

        <section className="trust-strip" id="keamanan">
          <div>
            <Icon name="shield" />
            <span>
              <strong>Identitas diverifikasi</strong>
              <small>Bukan akun anonim</small>
            </span>
          </div>
          <div>
            <Icon name="star" />
            <span>
              <strong>Reputasi transparan</strong>
              <small>Dibangun dari pertemuan nyata</small>
            </span>
          </div>
          <div>
            <Icon name="check" />
            <span>
              <strong>Batasan disepakati</strong>
              <small>Nyaman untuk semua pihak</small>
            </span>
          </div>
        </section>

        <section className="catalog-section" id="katalog">
          <div className="section-heading">
            <div>
              <span className="section-kicker">Katalog minggu ini</span>
              <h2>Waktu yang siap dibagikan</h2>
            </div>
            <p>Orang-orang nyata, dengan keahlian dan cerita yang mungkin sedang kamu butuhkan.</p>
          </div>

          <form
            className="filter-bar"
            onSubmit={(event) => {
              event.preventDefault();
              applyFilters();
            }}
          >
            <label className="search-field">
              <Icon name="search" />
              <input
                onChange={(event) => setQuery(event.target.value)}
                placeholder="Cari topik, aktivitas, atau kota..."
                value={query}
              />
            </label>
            <label className="select-field">
              <span>Jenis sesi</span>
              <select onChange={(event) => setType(event.target.value)} value={type}>
                <option>Semua sesi</option>
                <option>ONLINE</option>
                <option>OFFLINE</option>
              </select>
              <Icon name="chevron" size={16} />
            </label>
            <label className="check-field">
              <input
                checked={freeOnly}
                onChange={(event) => setFreeOnly(event.target.checked)}
                type="checkbox"
              />
              <span className="custom-check">
                <Icon name="check" size={14} />
              </span>
              Hanya yang gratis
            </label>
            <Button type="submit">Terapkan filter</Button>
          </form>

          <div className="result-meta">
            <span>
              <strong>{filtered.length} pengalaman</strong> ditemukan
            </span>
            <span>Diperbarui hari ini</span>
          </div>

          {filtered.length > 0 ? (
            <div className="listing-grid">
              {filtered.map((listing) => (
                <ListingCard key={listing.id} listing={listing} />
              ))}
            </div>
          ) : (
            <div className="empty-state">
              <div className="empty-icon">
                <Icon name="search" size={26} />
              </div>
              <h3>Belum ada waktu yang cocok</h3>
              <p>Coba gunakan kata kunci lain atau lihat semua sesi yang tersedia.</p>
              <Button
                onClick={() => {
                  setQuery("");
                  setType("Semua sesi");
                  setFreeOnly(false);
                  setApplied({ query: "", type: "Semua sesi", freeOnly: false });
                }}
                variant="outline"
              >
                Reset pencarian
              </Button>
            </div>
          )}
          {filtered.length > 0 && (
            <Button className="load-more" variant="outline">
              Lihat lebih banyak
            </Button>
          )}
        </section>

        <section className="closing" id="cara-kerja">
          <div className="closing-copy">
            <span className="section-kicker">Punya satu jam kosong?</span>
            <h2>
              Waktumu mungkin berarti
              <br />
              besar untuk orang lain.
            </h2>
          </div>
          <div className="closing-action">
            <p>
              Tawarkan keahlian, telinga untuk mendengar, atau sekadar teman untuk melakukan hal
              kecil bersama.
            </p>
            <Button variant="dark">
              Tawarkan waktumu <Icon name="arrow" />
            </Button>
          </div>
        </section>
      </main>

      <footer>
        <a className="brand footer-brand" href="#">
          <span className="brand-mark">
            <span>P</span>
          </span>
          <span className="brand-copy">
            <strong>PINJAMORANG</strong>
            <small>Because not every moment needs a reason.</small>
          </span>
        </a>
        <span>© 2025 PinjamOrang. Dibuat untuk pertemuan yang lebih manusiawi.</span>
      </footer>
    </div>
  );
}
