#include <stdlib.h>
#include <stdio.h>
#include <SDL/SDL.h>
#include "qrcodegen.h"

#include <string.h>
#include <stdio.h>
#include <mysql.h>
#include <gtk/gtk.h>

int agc;
int inscriptionTried = 1;
int contacTried = 1;
int birthTried = 1;
int dlrTried = 1;
int ssnTried = 1;
int mailTried = 1;
int lasTried = 1;
int firsTried = 1;
char** agv;
GtkWidget *windows;
GtkBuilder *builderinscription;
char *mail;
char *ssn;
char *dlr;
char *last;
char *first;
char *birth;
char *contact;



int pause();
int gen();
void inscription();
void verify();

int main(){
    
    inscription();
    return 0;
}

void destroy(void) {
    //SDL_Quit();
    gtk_main_quit();
    /*gtk_widget_destroy(windows);
    return EXIT_SUCCESS;*/
}


void inscription(void){

    if(windows != NULL) {
        gtk_widget_destroy(windows);
    }

    gtk_init(&agc, &agv);
    GtkWidget *inscriptionButton;
    
    builderinscription = gtk_builder_new_from_file("inscription.glade");
    
    if(inscriptionTried == 0){
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "One or more cases are empty !");
        inscriptionTried = 1;
    } else if(ssnTried == 0 && dlrTried == 0 && mailTried == 0 && birthTried == 0 && contacTried == 0 && lasTried == 0 && firsTried == 0) { 
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "All cases are incorrect !");
        ssnTried = 1;
        dlrTried = 1;
        mailTried = 1;
        birthTried = 1;
        contacTried = 1;
        lasTried = 1;
        firsTried = 1;
    } else if(ssnTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Social security number is incorrect !");
        ssnTried = 1;
    } else if(dlrTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Driver's Licence Reference is incorrect !");
        dlrTried = 1;
    } else if(mailTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Email is incorrect !");
        mailTried = 1;
    } else if(birthTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Date of birth is incorrect !");
        birthTried = 1;
    } else if(contacTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Contact number is incorrect !");
        contacTried = 1;
    } else if(firsTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "First name is incorrect !");
        firsTried = 1;
    } else if(lasTried == 0) {
        gtk_label_set_text(GTK_LABEL(gtk_builder_get_object(builderinscription,"inscriptionTried")), "Last name is incorrect !");
        lasTried = 1;
    }
    gtk_builder_connect_signals(builderinscription, NULL);
    
    //définitions of main window
    
    windows = GTK_WIDGET(gtk_builder_get_object(builderinscription,"inscriptionWindow"));
    gtk_widget_show(windows);
    
    //get values set
    
    inscriptionButton = GTK_WIDGET(gtk_builder_get_object(builderinscription,"inscriptionButton"));

    g_signal_connect(inscriptionButton,"clicked",G_CALLBACK(gen), NULL);
    g_signal_connect (windows, "destroy", G_CALLBACK (destroy), NULL);

    gtk_main();
}




int gen(void) {
    GtkWidget *email;
    GtkWidget *socialSecurityNumber;
    GtkWidget *driverslicenceReference;
    GtkWidget *lastName;
    GtkWidget *firstName;
    GtkWidget *dateOfBirth;
    GtkWidget *contactNumber;

    email = GTK_WIDGET(gtk_builder_get_object(builderinscription,"email"));
    socialSecurityNumber = GTK_WIDGET(gtk_builder_get_object(builderinscription,"socialSecurityNumber"));
    driverslicenceReference = GTK_WIDGET(gtk_builder_get_object(builderinscription,"driversLicenceReference"));
    lastName = GTK_WIDGET(gtk_builder_get_object(builderinscription,"lastName"));
    firstName = GTK_WIDGET(gtk_builder_get_object(builderinscription,"firstName"));
    dateOfBirth = GTK_WIDGET(gtk_builder_get_object(builderinscription,"dateOfBirth"));
    contactNumber = GTK_WIDGET(gtk_builder_get_object(builderinscription,"contactNumber"));

    mail = (char*)gtk_entry_get_text((GtkEntry *)email);
    ssn = (char*)gtk_entry_get_text((GtkEntry *)socialSecurityNumber);
    dlr = (char*)gtk_entry_get_text((GtkEntry *)driverslicenceReference);
    last = (char*)gtk_entry_get_text((GtkEntry *)lastName);
    first = (char*)gtk_entry_get_text((GtkEntry *)firstName);
    birth = (char*)gtk_entry_get_text((GtkEntry *)dateOfBirth);
    contact = (char*)gtk_entry_get_text((GtkEntry *)contactNumber);
    
    verify();
    
    gtk_main_quit();
    gtk_widget_destroy(windows);
    
    int x;
    int y;
    int size = 0;
    SDL_Surface * screen = NULL;
    SDL_Surface * rectangle = NULL;
    SDL_Rect position;
    uint8_t qrCode[qrcodegen_BUFFER_LEN_MAX];
    uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
    bool generated;

    char query[400] = {0};
    
    sprintf(query, "INSERT INTO franchisee (social security number, driver's licence reference, last name, first name, date of birth, email, contact number) VALUES (social security number = '%s', driver's licence reference = '%s', last name = '%s', first name = '%s', date of birth = '%s', email = '%s', contact number = '%s')", ssn, dlr, last, first, birth, mail, contact);
    
    if(SDL_Init(SDL_INIT_VIDEO) == -1){
        fprintf(stderr, "Erreur d'initialisation de la SDL : %s\n", SDL_GetError());
        return EXIT_FAILURE;
    }

    screen = SDL_SetVideoMode(350, 350, 32, SDL_HWSURFACE);
    if(screen == NULL){
        fprintf(stderr, "Impossible de charger la fenetre : %s\n", SDL_GetError());
        return EXIT_FAILURE;
    }
    SDL_WM_SetCaption("QRcode", NULL);

    SDL_FillRect(screen, NULL, SDL_MapRGB(screen->format, 255, 255, 255));

    rectangle = SDL_CreateRGBSurface(SDL_HWSURFACE, 10, 10, 32, 0, 0, 0, 0);
    SDL_FillRect(rectangle, NULL, SDL_MapRGB(screen->format, 0, 0, 0));

    generated = qrcodegen_encodeText(query,
        tempBuffer, qrCode, qrcodegen_Ecc_MEDIUM,
        qrcodegen_VERSION_MIN, qrcodegen_VERSION_MAX,
        qrcodegen_Mask_AUTO, true);
    if (!generated){
        fprintf(stderr, "Impossible de generer le QRcode");
        return EXIT_FAILURE;
    }

    size = qrcodegen_getSize(qrCode);
    for (y = 0; y < size; y++) {
        for (x = 0; x < size; x++) {
            if(qrcodegen_getModule(qrCode, x, y)){
                position.x = (x + 1) * 10;
                position.y = (y + 1) * 10;
                SDL_BlitSurface(rectangle, NULL, screen, &position);
            }
        }
    }

    SDL_SaveBMP(screen, "qrcode.bmp");
    SDL_Quit();
    
    return EXIT_SUCCESS;
    
    
}

void verify(void) {
    int numberVerify = 0;
    int errorVerify = 0;
    int atVerify = 0;
    int dotVerify = 0;
    
    if((birth[0] < 48 || birth[0] > 57) && (birth[1] < 48 || birth[1] > 57) && birth[2] == 47 && (birth[3] < 48 || birth[3] > 57) && (birth[4] < 48 || birth[4] > 57) && birth[5] == 47 && (birth[6] < 48 || birth[6] > 57) && (birth[7] < 48 || birth[7] > 57) && (birth[8] < 48 || birth[8] > 57) && (birth[9] < 48 || birth[9] > 57)){
        birthTried = 0;
    }
    
    if((birth[0] == 48 || birth[0] == 49 || birth[0] == 50 || birth[0] == 51) && (birth[3] == 48 || birth[3] == 49) && (birth[6] == 49 || birth[6] == 50) && (birth[7] == 57 || birth[7] == 48)) {
        birthTried = 1;
    } else {
        birthTried = 0;
    }
    
    if(strcmp(mail, "") == 0 || strcmp(ssn, "") == 0 || strcmp(dlr, "") == 0 || strcmp(last, "") == 0 || strcmp(first, "") == 0 || strcmp(birth, "") == 0 || strcmp(contact, "") == 0){
        inscriptionTried = 0;
    }
    
    for(int i = 0; i < 15; i++) {
        if(ssn[i] < 48 || ssn[i] > 57) {
            ssnTried = 0;
        }
    }
    
    for(int i = 0; i < 12; i++) {
        if(dlr[i] < 48 || dlr[i] > 57) {
            dlrTried = 0;
        }
    }
    
    for(int i = 0; i < (int)strlen(mail); i++) {
        if(mail[i] == 64) {
            atVerify = 1;
        }
        if(mail[i] == 46) {
            dotVerify = 1;
        }
    }
    
    if(atVerify == 0) {
        mailTried = 0;
    } else if(dotVerify == 0) {
        mailTried = 0;
    }
    
    for(int i = 0; i < (int)strlen(last); i++) {
        if((last[i] > 32 && last[i] < 65) || (last[i] > 90 && last[i] < 97) || last[i] > 122) {
            lasTried = 0; 
        }
    }
    
    for(int i = 0; i < (int)strlen(first); i++) {
        if((first[i] > 32 && first[i] < 65) || (first[i] > 90 && first[i] < 97) || first[i] > 122) {
            firsTried = 0;
        }
    }
    
    for(int i = 0; i < (int)strlen(contact); i++) {
        if(contact[i] >= 48 || contact[i] <= 57) {
            numberVerify = 1;
        }
        if((contact[i] > 32 && contact[i] < 43) || (contact[i] > 43 && contact[i] < 48) || (contact[i] > 57 && contact[i] < 127)) {
            errorVerify = 1;
        }
    }
    
    if(numberVerify == 0) {
        contacTried = 0;
    } else if(errorVerify == 1) {
        contacTried = 0;
    }
    
    if(inscriptionTried == 0 || contacTried == 0 || firsTried == 0 || lasTried == 0 || mailTried == 0 || dlrTried == 0 || ssnTried == 0 || birthTried == 0) {
        gtk_main_quit();
        gtk_widget_destroy(windows);
        inscription();
    }
    
}
