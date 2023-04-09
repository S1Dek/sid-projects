#include <iostream>         // biblioteka i/o
#include <locale.h>         // biblioteka ustawienia specyficzne dla lokalizacji

using namespace std;

//funkcja odpowiedzialna za wpisywanie to tablicy
void input_table(int size_x, int size_y, int**& table) {  // dodano referencję do tablicy
    table = new int* [size_x];
    for (int i = 0; i < size_x; i++) {
        table[i] = new int[size_y];
        for (int j = 0; j < size_y; j++) {
            cout << "Podaj wartość dla elementu [" << i << "][" << j << "]: ";
            cin >> table[i][j];                             // wpisywanie wszystkich elementów tablicy
        }
    }
}
//funkcja odpowiedzialna za wypisywanie danych z tablicy
void output_table(int size_x, int size_y, int** table) {
    for (int i = 0; i < size_x; i++) {
        cout << "|";
        for (int j = 0; j < size_y; j++) {
            cout << table[i][j] << "|";
        }
        cout << "" << endl;
    }
}
//funkcja odpowiedzialna za wyświetlanie menu
void menu() {
    int choice;
    int size_x = 0, size_y = 0;
    int** table = nullptr;  // inicjalizacja wartości wskaźnika

    do {
        cout << "---------MENU----------" << endl;
        cout << "| 1. Wpisz do tablicy |" << endl;
        cout << "| 2. Wypisz z tablicy |" << endl;
        cout << "|     3. ZAKOŃCZ      |" << endl;
        cout << "-----------------------" << endl;
        cin >> choice;
        if (choice == 1) {
            cout << "Podaj dwa parametry tablicy" << endl;
            cin >> size_x >> size_y;
            input_table(size_x, size_y, table);
        }
        else if (choice == 2) {
            if (size_x < 1 || size_y < 1) {
                cout << "Zdefinuj wartości wielkości tablicy." << endl;
            }
            else {
                output_table(size_x, size_y, table);
            }
        }
        else if (choice == 3) {
            cout << "*********  KONIEC  *********" << endl;
        }
        else {
            cout << "Nie ma takiego wyboru, wybierz ponownie" << endl;
        }
    } while (choice != 3);

    // zwolnienie pamięci po tablicy przed zakończeniem programu
    if (table != nullptr) {
        for (int i = 0; i < size_x; i++) {
            delete[] table[i];
        }
        delete[] table;
    }
}
int main() {
    setlocale(LC_ALL, "");  // UTF-8 polskie znaki
    menu();
    return 0;
}
