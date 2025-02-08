Założenia projektowe spiewnik ONE light, poprawka 3
# 1. Opis projektu
spiewnik to internetowy system gromadzenia i wyszukiwania pieśni, a także tworzenia z nich prezentacji.
# 2. Cel
spiewnik powstał w celu ujednolicenia pieśni, ułatwienia wyszukiwania tekstów i akordów, a także dla częściowego zautomatyzowania tworzenia prezentacji z tych pieśni. Jest on przeznaczony głównie na cele wspólnoty młodzieżowej MAGIS, ale dostęp do niego nie jest w żaden sposób ograniczony dla osób postronnych.
# 3. Struktura
spiewnik składa się z następujących części:
* baza danych napisana w języku MySQL
* strona serwera napisana w języku PHP, obsługująca bazę danych
* strona użytkownika napisana głównie w językach PHP, HTML, CSS oraz JS, umożliwiająca użytkownikowi przeglądanie, dodawanie, edycję i usuwanie pieśni oraz prezentacji
## 3.1 baza danych
Stworzona z użyciem języka MySQL, tabele:
* user (id, login, displayName, password, cityId, joinDate, authorizationLevel) - dane kont użytkowników
* city (id, cityName) - wspólnota użytkownika lub "inne"
* song (id, title, userId, text, chord, uploadDate) - pieśń i akordy
* presentation (id, title, userId, songs, uploadDate, isPermanent) - prezentacja
## 3.2 strona serwera
Pobiera dane z bazy danych i przesyła je do strony użytkownika, powinna umożliwiać:
* tworzenie konta
    * sprawdzenie danych
    * hashowanie hasła
    * dodanie danych do serwera
    * przeniesienie do strony logowania
* logowanie
    * sprawdzenie danych
    * pobranie użytkownika z podanym loginem
    * weryfikacja hashu hasła
    * utworzenie sesji
    * dodanie zmiennej sesji o wartości poziomu uprawnień
    * przeniesienie na stronę główną
* wylogowywanie
    * usunięcie danych sesji
    * zamknięcie sesji
    * przeniesienie na stronę główną
* pobieranie listy użytkowników (bez hashy)
    * funkcja możliwa do wywołania w php
* pobieranie listy miast
    * funkcja możliwa do wywołania w php
* pobieranie, dodawanie, edycję i usuwanie pieśni
    * funkcja możliwa do wywołania w php
* pobieranie, dodawanie, edycję i usuwanie prezentacji
    * funkcja możliwa do wywołania w php
## 3.3 strona użytkownika
Witryna internetowa, graficzny interfejs, umożliwia użytkownikowi łatwą i intuicyjną komunikację ze stroną serwera.
### 3.3.1 szablon ogólny:
* nawigacja u góry przekierowuje na główne strony - index, songs, presentations, (register, login) lub user/me.php
* stopka na dole zawiera informacje o wersji, autorze oraz dane kontaktowe, oraz odnośnik do github'a projektu, odnośnik pomoc
### 3.3.2 index
* strona główna
* ostatnie 10 utworzonych prezentacji
* ostatnie 10 dodanych pieśni
* odnośnik do ogólnej instrukcji
### 3.3.3 songs
* wyświetla pieśni w formacie id, title, user, uploadDate każda pieśń odnosi do podstrony song danej pieśni
* 24 pieśni na stronę, obsługa w PHP
* odnośnik do podstrony new.php
* pasek wyszukiwania według tytułu
### 3.3.4 song
* wyświetla informacje o pieśni według podanego id
* id, title
* user, będący odnośnikiem do odpowiedniej strony user
* text, chord wyświetlone obok siebie w tabeli
* upload date
* przycisk odnoszący do strony edit.php
* przycisk usuwający za potwierdzeniem daną pieśń
### 3.3.5 song/edit.php
* pozwala edytować istniejącą pieśń
* dostęp tylko po zalogowaniu
* wyświetla tytuł, tekst i akordy w polach edycyjnych
* tekst i akordy są podzielone odpowiednio na pola zwrotki i zwrotki akordów
* przycisk tworzący dodatkowe pole zwrotka i zwrotka akordów
* każde pole zwrotka, za wyjątkiem pierwszego, posiada przycisk usuwający dane pole i odpowiadające pole zwrotki akordów
* przycisk wysyłający dane (w tym zwrotki i zwrotki akordów w formacie JSON) do skryptu strony serwera aktualizującego dane w bazie
### 3.3.6 songs/new.php
* pozwala dodać nową pieśń do bazy danych
* dostęp tylko po zalogowaniu
* odnośnik do instrukcji dodawania pieśni
* pole tytuł
* pole zwrotka
* każde pole zwrotka zawiera pole zwrotka akordów
* przycisk tworzący dodatkowe pole zwrotka i zwrotka akordów
* każde pole zwrotka, za wyjątkiem pierwszego, posiada przycisk usuwający dane pole i odpowiadające pole zwrotki akordów
* przycisk wysyłający dane (w tym zwrotki i zwrotki akordów w formacie JSON) do skryptu strony serwera dodającego dane do bazy
### 3.3.7 presentations
* wyświetla prezentacje w formacie id, title, user, upload date po najechaniu wyświetla tytuły pieśni zawartych w prezentacji, każda prezentacja odnosi do podstrony presentation danej prezentacji
* 24 prezentacje na stronę, obsługa w PHP
* odnośnik do podstrony new.php
* pasek wyszukiwania według tytułu
### 3.3.8 presentation
* wyświetla informacje o prezentacji według podanego id
* id, title
* user, będący odnośnikiem do odpowiedniej strony user
* lista tytułów pieśni, każdy odnosi do strony song danej pieśni
* upload date
* przycisk odnoszący do strony edit.php
* przycisk usuwający za potwierdzeniem daną prezentację
* przycisk uruchamiający daną prezentację
### 3.3.9 presentation/edit.php
* pozwala edytować istniejącą prezentację
* dostęp tylko po zalogowaniu
* przycisk odświeżający zapisaną lokalnie listę pieśni
* wyświetla tytuł w polu edycyjnym
* lista pieśni
* każda pieśń posiada przyciski przesuwające ją w górę lub w dół w kolejności oraz przycisk usuń
* przycisk dodania nowej pieśni do prezentacji, otwierający pole wyszukiwania, obsługa w JS
* przycisk wysyłający dane do skryptu strony serwera aktualizującego dane w bazie
### 3.3.10 presentation/new.php
* pozwala dodać nową prezentację do bazy danych
* dostęp tylko po zalogowaniu
* odnośnik do instrukcji tworzenia prezentacji
* przycisk odświeżający zapisaną lokalnie listę pieśni
* pole tytuł
* przycisk dodania nowej pieśni do prezentacji, otwierający pole wyszukiwania, obsługa w JS
* każda dodana pieśń posiada przyciski przesuwające ją w górę lub w dół w kolejności oraz przycisk usuń
* checkbox stałej prezentacji
* przycisk wysyłający dane do skryptu strony serwera dodającego dane do bazy
### 3.3.11 presentation/show
* ekran prezentacji
* otwierana w nowym oknie, możliwie najbardziej pozbawionym cech własnych przeglądarki (zakładek, pasku bocznego itd)
* wyświetla osobno, na każdym slajdzie tekst jednej zwrotki
* poszczególne pieśni oddzielone są pustym slajdem
* puste slajdy na początku i końcu
* następny slajd - strzałka w prawo, lewy przycisk myszy
* poprzedni slajd - strzałka w lewo, prawy przycisk myszy
* przełączenie motywu (jasny, ciemny) - m
* początek prezentacji - r
* zamknięcie prezentacji - escape
### 3.3.12 register
* pozwala utworzyć nowe konto użytkownika
* odnośnik do instrukcji
* pola login, nazwa, hasło i wybór miasta z listy
* odnośnik do regulaminu i oświadczenia o plikach cookie
* potwierdzenie przeczytania regulaminu i oświadczenia o plikach cookie
* przycisk utwórz konto
### 3.3.13 login
* pozwala zalogować się na istniejące konto
* pola login, hasło
* przycisk zaloguj
### 3.3.14 user
* nazwa
* miasto
* data dołączenia
* poziom uprawnień
### 3.3.15 user/me.php
* id, nazwa
* login
* miasto
* data dołączenia
* poziom uprawnień
### 3.3.16 presentation/live
* ekran tworzenia prezentacji na żywo
* nie wymaga logowania
* użytkownik wybiera pieśni i tworzy z nich prezentację bez możliwości jej zapisu
* zalogowany użytkownik może również zapisać prezentację
* przycisk dodania nowej pieśni do prezentacji, otwierający pole wyszukiwania, obsługa w JS
* każda dodana pieśń posiada przyciski przesuwające ją w górę lub w dół w kolejności oraz przycisk usuń
* po naciśnięciu uruchom, prezentacja otwiera się w nowym oknie
* jeżeli nowe okno jest już otwarte, po wprowadzeniu zmian zaktualizowana prezentacja otwiera się w tym samym, już otwartym oknie
### 3.3.17 user/admin.php
* narzędzia administratora
* dostęp tylko dla administracji (4 poziom uprawnień)
* nadawanie uprawnień użytkownikom do poziomu 3 włącznie
* usuwanie wszystkich prezentacji nie oznaczonych jako stałe
# 4. Objaśnienia
## Dla użytkowników
### 4.1 miasto
Ułatwia identyfikację użytkownika
### 4.2 login i nazwa
login służy do logowania i weryfikacji, nazwa się wyświetla
### 4.3 poziom uprawnień
* 0 - domyślny, nie pozwala na nic
* 1 - pozwala tworzyć nowe i edytować dodane przez siebie prezentacje
* 2 - pozwala dodawać nowe i edytować dodane przez siebie pieśni
* 3 - pozwala edytować i usuwać wszystkie pieśni i prezentacje
* 4 - administrator
### 4.3 weryfikacja
Każdy użytkownik musi zostać zweryfikowany aby otrzymać wyższy poziom uprawnień, weryfikacja odbywa się:
* osobiście u twórcy strony lub osób uprawnionych (określonych w późniejszym czasie)
* przez Discord: **m4gnez**

Wymagania na konkretne stopnie:
* 1 - prosty wniosek "id, login"
* 2 - prosty wniosek "id, login oraz powód, dla którego chcę mieć ten poziom uprawnień"
* 3 - prosty wniosek "id, login, miasto, mój animator oraz uzasadnienie", potwierdzenie podanego animatora
* 4 - ustalany jedynie przez twórcę
### 4.4 pliki cookie
Oświadczenie jest obecne dopiero przy zakładaniu konta, ponieważ jedyną funkcjonalnością wymagającą plików cookie jest sesja PHP, potrzebna do obsługi kont.
## Dla prezentacji
### 4.5 motyw
Zauważono, że przy mocnym świetle, np. słonecznym lepiej widać czarne napisy na białym tle niż w przeciwnym wypadku
* domyślnie białe napisy na czarnym tle
* alternatywnie czarne napisy na białym tle
### 4.6 lokalna lista pieśni i jej odświeżanie
Tytuły pieśni są zapisane lokalnie, w celu ułatwienia wyszukiwania. Odświeżenie listy jest konieczne, kiedy w trakcie robienia prezentacji chcemy dodać pieśń, która dopiero co została dodana. Żeby nie tracić postępu prezentacji, należy odświeżyć listę.
### 4.7 stała prezentacja
Wszystkie prezentacje nieoznaczone jako stałe, mogą być usunięte jednym przyciskiem
# 5. Bezpieczeństwo
* hasła są hashowane