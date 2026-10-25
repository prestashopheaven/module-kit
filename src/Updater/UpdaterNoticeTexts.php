<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 *
 * @author    George van Engers | PrestaHaven.com
 * @copyright Since 2026 George van Engers
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShopHeaven\ModuleKit\Updater;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * The texts of the updater notice in the 33 languages of the PrestaHaven modules. They live here (not in each
 * module's translations) because the notice is the same in every module and only exists in the direct-sale build.
 * Keys: title, text, install, installing, installed, open, failed, download, info, hide. %s in title = module name.
 */
final class UpdaterNoticeTexts
{
    /** PrestaShop language codes that differ from ISO 639-1. */
    private const PRESTASHOP_ISO = ['si' => 'sl', 'vn' => 'vi', 'tw' => 'zh', 'gb' => 'en', 'br' => 'pt', 'mx' => 'es', 'qc' => 'fr'];

    private const TEXTS = [
        'en' => ['Get one-click updates for %s', 'Install the free PrestaHaven Updater: it updates this module and your other PrestaHaven modules from your back office, safely and only after your confirmation.', 'Install now', 'Installing…', 'The PrestaHaven Updater is installed.', 'Open the updates', 'The installation did not work:', 'Download it', 'More information', 'Hide for 30 days'],
        'fr' => ['Mises à jour en un clic pour %s', 'Installez le PrestaHaven Updater gratuit : il met à jour ce module et vos autres modules PrestaHaven depuis votre back-office, en toute sécurité et uniquement après votre confirmation.', 'Installer maintenant', 'Installation…', 'Le PrestaHaven Updater est installé.', 'Voir les mises à jour', "L'installation n'a pas fonctionné :", 'Le télécharger', "Plus d'informations", 'Masquer pendant 30 jours'],
        'es' => ['Actualizaciones con un clic para %s', 'Instale el PrestaHaven Updater gratuito: actualiza este módulo y sus demás módulos PrestaHaven desde su back office, de forma segura y solo tras su confirmación.', 'Instalar ahora', 'Instalando…', 'El PrestaHaven Updater está instalado.', 'Ver las actualizaciones', 'La instalación no ha funcionado:', 'Descargarlo', 'Más información', 'Ocultar durante 30 días'],
        'it' => ['Aggiornamenti con un clic per %s', 'Installa il PrestaHaven Updater gratuito: aggiorna questo modulo e gli altri moduli PrestaHaven dal tuo back office, in sicurezza e solo dopo la tua conferma.', 'Installa ora', 'Installazione…', 'Il PrestaHaven Updater è installato.', 'Apri gli aggiornamenti', "L'installazione non è riuscita:", 'Scaricalo', 'Maggiori informazioni', 'Nascondi per 30 giorni'],
        'pl' => ['Aktualizacje jednym kliknięciem dla %s', 'Zainstaluj darmowy PrestaHaven Updater: aktualizuje ten moduł i pozostałe moduły PrestaHaven z panelu administracyjnego, bezpiecznie i tylko po Twoim potwierdzeniu.', 'Zainstaluj teraz', 'Instalowanie…', 'PrestaHaven Updater jest zainstalowany.', 'Otwórz aktualizacje', 'Instalacja nie powiodła się:', 'Pobierz go', 'Więcej informacji', 'Ukryj na 30 dni'],
        'de' => ['Updates mit einem Klick für %s', 'Installieren Sie den kostenlosen PrestaHaven Updater: Er aktualisiert dieses Modul und Ihre anderen PrestaHaven-Module aus Ihrem Backoffice, sicher und nur nach Ihrer Bestätigung.', 'Jetzt installieren', 'Wird installiert…', 'Der PrestaHaven Updater ist installiert.', 'Updates öffnen', 'Die Installation hat nicht funktioniert:', 'Herunterladen', 'Mehr Informationen', '30 Tage ausblenden'],
        'sl' => ['Posodobitve z enim klikom za %s', 'Namestite brezplačni PrestaHaven Updater: ta modul in vaše druge module PrestaHaven posodablja iz skrbniškega dela, varno in samo po vaši potrditvi.', 'Namesti zdaj', 'Nameščanje…', 'PrestaHaven Updater je nameščen.', 'Odpri posodobitve', 'Namestitev ni uspela:', 'Prenesite ga', 'Več informacij', 'Skrij za 30 dni'],
        'ar' => ['تحديثات بنقرة واحدة لـ %s', 'ثبّت PrestaHaven Updater المجاني: يحدّث هذه الوحدة ووحدات PrestaHaven الأخرى من لوحة الإدارة، بأمان وفقط بعد تأكيدك.', 'ثبّت الآن', 'جارٍ التثبيت…', 'تم تثبيت PrestaHaven Updater.', 'افتح التحديثات', 'لم ينجح التثبيت:', 'نزّله', 'مزيد من المعلومات', 'إخفاء لمدة 30 يومًا'],
        'bg' => ['Актуализации с едно кликване за %s', 'Инсталирайте безплатния PrestaHaven Updater: той актуализира този модул и другите ви модули PrestaHaven от административния панел, безопасно и само след вашето потвърждение.', 'Инсталирай сега', 'Инсталиране…', 'PrestaHaven Updater е инсталиран.', 'Отвори актуализациите', 'Инсталирането не успя:', 'Изтеглете го', 'Повече информация', 'Скрий за 30 дни'],
        'cs' => ['Aktualizace jedním kliknutím pro %s', 'Nainstalujte si zdarma PrestaHaven Updater: aktualizuje tento modul a vaše další moduly PrestaHaven z administrace, bezpečně a jen po vašem potvrzení.', 'Nainstalovat nyní', 'Instalace…', 'PrestaHaven Updater je nainstalován.', 'Otevřít aktualizace', 'Instalace se nezdařila:', 'Stáhnout', 'Více informací', 'Skrýt na 30 dní'],
        'da' => ['Opdateringer med ét klik til %s', 'Installer den gratis PrestaHaven Updater: den opdaterer dette modul og dine andre PrestaHaven-moduler fra din back office, sikkert og kun efter din bekræftelse.', 'Installer nu', 'Installerer…', 'PrestaHaven Updater er installeret.', 'Åbn opdateringerne', 'Installationen lykkedes ikke:', 'Download den', 'Mere information', 'Skjul i 30 dage'],
        'el' => ['Ενημερώσεις με ένα κλικ για το %s', 'Εγκαταστήστε το δωρεάν PrestaHaven Updater: ενημερώνει αυτή την ενότητα και τις άλλες ενότητες PrestaHaven από το back office σας, με ασφάλεια και μόνο μετά την επιβεβαίωσή σας.', 'Εγκατάσταση τώρα', 'Εγκατάσταση…', 'Το PrestaHaven Updater εγκαταστάθηκε.', 'Άνοιγμα ενημερώσεων', 'Η εγκατάσταση δεν πέτυχε:', 'Λήψη', 'Περισσότερες πληροφορίες', 'Απόκρυψη για 30 ημέρες'],
        'et' => ['Ühe klõpsuga uuendused moodulile %s', 'Paigaldage tasuta PrestaHaven Updater: see uuendab seda moodulit ja teie teisi PrestaHaveni mooduleid haldusliidesest, turvaliselt ja ainult pärast teie kinnitust.', 'Paigalda kohe', 'Paigaldamine…', 'PrestaHaven Updater on paigaldatud.', 'Ava uuendused', 'Paigaldamine ebaõnnestus:', 'Laadi see alla', 'Rohkem teavet', 'Peida 30 päevaks'],
        'fi' => ['Päivitykset yhdellä napsautuksella: %s', 'Asenna ilmainen PrestaHaven Updater: se päivittää tämän moduulin ja muut PrestaHaven-moduulisi hallintapaneelista turvallisesti ja vain vahvistuksesi jälkeen.', 'Asenna nyt', 'Asennetaan…', 'PrestaHaven Updater on asennettu.', 'Avaa päivitykset', 'Asennus ei onnistunut:', 'Lataa se', 'Lisätietoja', 'Piilota 30 päiväksi'],
        'hu' => ['Egykattintásos frissítések: %s', 'Telepítse az ingyenes PrestaHaven Updatert: frissíti ezt a modult és többi PrestaHaven modulját az adminisztrációs felületről, biztonságosan és csak az Ön jóváhagyása után.', 'Telepítés most', 'Telepítés…', 'A PrestaHaven Updater telepítve van.', 'Frissítések megnyitása', 'A telepítés nem sikerült:', 'Letöltés', 'További információ', 'Elrejtés 30 napra'],
        'id' => ['Pembaruan sekali klik untuk %s', 'Pasang PrestaHaven Updater gratis: memperbarui modul ini dan modul PrestaHaven Anda lainnya dari back office, dengan aman dan hanya setelah konfirmasi Anda.', 'Pasang sekarang', 'Memasang…', 'PrestaHaven Updater sudah terpasang.', 'Buka pembaruan', 'Pemasangan tidak berhasil:', 'Unduh', 'Informasi lebih lanjut', 'Sembunyikan selama 30 hari'],
        'ja' => ['%s をワンクリックで更新', '無料の PrestaHaven Updater をインストールしてください。このモジュールとほかの PrestaHaven モジュールを管理画面から、安全に、確認後にのみ更新します。', '今すぐインストール', 'インストール中…', 'PrestaHaven Updater がインストールされました。', '更新を開く', 'インストールできませんでした:', 'ダウンロード', '詳細情報', '30日間非表示'],
        'ko' => ['%s 원클릭 업데이트', '무료 PrestaHaven Updater를 설치하세요. 이 모듈과 다른 PrestaHaven 모듈을 관리자 화면에서 안전하게, 확인 후에만 업데이트합니다.', '지금 설치', '설치 중…', 'PrestaHaven Updater가 설치되었습니다.', '업데이트 열기', '설치하지 못했습니다:', '다운로드', '자세한 정보', '30일 동안 숨기기'],
        'lt' => ['Atnaujinimai vienu spustelėjimu: %s', 'Įdiekite nemokamą PrestaHaven Updater: jis atnaujina šį modulį ir kitus jūsų PrestaHaven modulius iš administravimo skydelio, saugiai ir tik jums patvirtinus.', 'Įdiegti dabar', 'Diegiama…', 'PrestaHaven Updater įdiegtas.', 'Atidaryti atnaujinimus', 'Įdiegti nepavyko:', 'Atsisiųsti', 'Daugiau informacijos', 'Slėpti 30 dienų'],
        'lv' => ['Atjauninājumi ar vienu klikšķi: %s', 'Instalējiet bezmaksas PrestaHaven Updater: tas atjaunina šo moduli un citus jūsu PrestaHaven moduļus no administrēšanas paneļa, droši un tikai pēc jūsu apstiprinājuma.', 'Instalēt tagad', 'Instalē…', 'PrestaHaven Updater ir instalēts.', 'Atvērt atjauninājumus', 'Instalēšana neizdevās:', 'Lejupielādēt', 'Vairāk informācijas', 'Paslēpt uz 30 dienām'],
        'no' => ['Oppdateringer med ett klikk for %s', 'Installer den gratis PrestaHaven Updater: den oppdaterer denne modulen og de andre PrestaHaven-modulene dine fra administrasjonen, trygt og bare etter at du har bekreftet.', 'Installer nå', 'Installerer…', 'PrestaHaven Updater er installert.', 'Åpne oppdateringene', 'Installasjonen fungerte ikke:', 'Last den ned', 'Mer informasjon', 'Skjul i 30 dager'],
        'nl' => ['Updates met één klik voor %s', 'Installeer de gratis PrestaHaven Updater: die werkt deze module en je andere PrestaHaven-modules bij vanuit je back-office, veilig en alleen na jouw bevestiging.', 'Nu installeren', 'Bezig met installeren…', 'De PrestaHaven Updater is geïnstalleerd.', 'Naar de updates', 'De installatie is niet gelukt:', 'Download hem', 'Meer informatie', '30 dagen verbergen'],
        'pt' => ['Atualizações com um clique para %s', 'Instale o PrestaHaven Updater gratuito: atualiza este módulo e os seus outros módulos PrestaHaven a partir do back office, com segurança e só depois da sua confirmação.', 'Instalar agora', 'A instalar…', 'O PrestaHaven Updater está instalado.', 'Abrir as atualizações', 'A instalação não funcionou:', 'Transferir', 'Mais informações', 'Ocultar durante 30 dias'],
        'ro' => ['Actualizări cu un clic pentru %s', 'Instalați gratuit PrestaHaven Updater: actualizează acest modul și celelalte module PrestaHaven din back office, în siguranță și doar după confirmarea dvs.', 'Instalează acum', 'Se instalează…', 'PrestaHaven Updater este instalat.', 'Deschide actualizările', 'Instalarea nu a reușit:', 'Descărcați-l', 'Mai multe informații', 'Ascunde 30 de zile'],
        'ru' => ['Обновления в один клик для %s', 'Установите бесплатный PrestaHaven Updater: он обновляет этот модуль и другие ваши модули PrestaHaven из панели управления, безопасно и только после вашего подтверждения.', 'Установить сейчас', 'Установка…', 'PrestaHaven Updater установлен.', 'Открыть обновления', 'Установка не удалась:', 'Скачайте его', 'Подробнее', 'Скрыть на 30 дней'],
        'sk' => ['Aktualizácie jedným kliknutím pre %s', 'Nainštalujte si bezplatný PrestaHaven Updater: aktualizuje tento modul a vaše ďalšie moduly PrestaHaven z administrácie, bezpečne a len po vašom potvrdení.', 'Nainštalovať teraz', 'Inštalácia…', 'PrestaHaven Updater je nainštalovaný.', 'Otvoriť aktualizácie', 'Inštalácia sa nepodarila:', 'Stiahnuť', 'Viac informácií', 'Skryť na 30 dní'],
        'sq' => ['Përditësime me një klik për %s', 'Instaloni falas PrestaHaven Updater: përditëson këtë modul dhe modulet e tjera PrestaHaven nga paneli i administrimit, në mënyrë të sigurt dhe vetëm pas konfirmimit tuaj.', 'Instalo tani', 'Duke instaluar…', 'PrestaHaven Updater është instaluar.', 'Hap përditësimet', 'Instalimi nuk funksionoi:', 'Shkarkojeni', 'Më shumë informacion', 'Fshih për 30 ditë'],
        'sv' => ['Uppdateringar med ett klick för %s', 'Installera den kostnadsfria PrestaHaven Updater: den uppdaterar den här modulen och dina andra PrestaHaven-moduler från administrationen, säkert och bara efter din bekräftelse.', 'Installera nu', 'Installerar…', 'PrestaHaven Updater är installerad.', 'Öppna uppdateringarna', 'Installationen fungerade inte:', 'Ladda ner den', 'Mer information', 'Dölj i 30 dagar'],
        'th' => ['อัปเดตในคลิกเดียวสำหรับ %s', 'ติดตั้ง PrestaHaven Updater ฟรี: อัปเดตโมดูลนี้และโมดูล PrestaHaven อื่นๆ ของคุณจากหลังร้าน อย่างปลอดภัยและเฉพาะหลังจากที่คุณยืนยันเท่านั้น', 'ติดตั้งตอนนี้', 'กำลังติดตั้ง…', 'ติดตั้ง PrestaHaven Updater แล้ว', 'เปิดการอัปเดต', 'การติดตั้งไม่สำเร็จ:', 'ดาวน์โหลด', 'ข้อมูลเพิ่มเติม', 'ซ่อน 30 วัน'],
        'tr' => ['%s için tek tıkla güncellemeler', 'Ücretsiz PrestaHaven Updater\'ı yükleyin: bu modülü ve diğer PrestaHaven modüllerinizi yönetim panelinden, güvenle ve yalnızca onayınızdan sonra günceller.', 'Şimdi yükle', 'Yükleniyor…', 'PrestaHaven Updater yüklendi.', 'Güncellemeleri aç', 'Yükleme başarısız oldu:', 'İndirin', 'Daha fazla bilgi', '30 gün gizle'],
        'uk' => ['Оновлення в один клік для %s', 'Встановіть безкоштовний PrestaHaven Updater: він оновлює цей модуль та інші ваші модулі PrestaHaven з панелі керування, безпечно і лише після вашого підтвердження.', 'Встановити зараз', 'Встановлення…', 'PrestaHaven Updater встановлено.', 'Відкрити оновлення', 'Встановлення не вдалося:', 'Завантажте його', 'Докладніше', 'Сховати на 30 днів'],
        'vi' => ['Cập nhật một lần nhấp cho %s', 'Cài đặt PrestaHaven Updater miễn phí: cập nhật mô-đun này và các mô-đun PrestaHaven khác của bạn từ trang quản trị, an toàn và chỉ sau khi bạn xác nhận.', 'Cài đặt ngay', 'Đang cài đặt…', 'PrestaHaven Updater đã được cài đặt.', 'Mở các bản cập nhật', 'Cài đặt không thành công:', 'Tải xuống', 'Thêm thông tin', 'Ẩn trong 30 ngày'],
        'zh' => ['一键更新 %s', '安装免费的 PrestaHaven Updater：它可在后台安全地更新此模块和您的其他 PrestaHaven 模块，并且只在您确认后执行。', '立即安装', '正在安装…', 'PrestaHaven Updater 已安装。', '打开更新', '安装未成功：', '下载', '更多信息', '隐藏 30 天'],
    ];

    private const KEYS = ['title', 'text', 'install', 'installing', 'installed', 'open', 'failed', 'download', 'info', 'hide'];

    /**
     * The texts for a PrestaShop language code (English when the language is not available).
     *
     * @return array<string, string> keyed by title, text, install, installing, installed, open, failed, download,
     *                               info, hide
     */
    public static function forLanguage(string $prestashopIso): array
    {
        $iso = strtolower(trim($prestashopIso));
        $iso = self::PRESTASHOP_ISO[$iso] ?? $iso;

        return array_combine(self::KEYS, self::TEXTS[$iso] ?? self::TEXTS['en']);
    }

    /**
     * The language codes that have texts.
     *
     * @return list<string>
     */
    public static function languages(): array
    {
        return array_keys(self::TEXTS);
    }
}
