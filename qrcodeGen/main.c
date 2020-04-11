#include <stdlib.h>
#include <stdio.h>
#include <SDL/SDL.h>
#include "qrcodegen.h"

void pause();

int main(int argc, char ** argv)
{
    int x;
    int y;
    int size = 0;
    SDL_Surface * screen = NULL;
    SDL_Surface * rectangle = NULL;
    SDL_Rect position;
    uint8_t qrCode[qrcodegen_BUFFER_LEN_MAX];
    uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
    bool generated;

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

    generated = qrcodegen_encodeText("Hello world! This is meeee! Come back to meee!",
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

    SDL_Flip(screen);
    SDL_SaveBMP(screen, "qrcode.bmp");
    pause();
    SDL_FreeSurface(rectangle);
    SDL_Quit();
    return EXIT_SUCCESS;
}

void pause()
{
    bool toContinue = true;
    SDL_Event event;

    while(toContinue)
    {
        SDL_WaitEvent(&event);
        switch(event.type)
        {
            case SDL_QUIT:
                toContinue = false;
        }
    }
}
