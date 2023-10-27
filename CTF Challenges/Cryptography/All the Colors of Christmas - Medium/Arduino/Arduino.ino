#include <FastLED.h>
#define NUM_LEDS 50
CRGB leds[NUM_LEDS];
void setup() { FastLED.addLeds<WS2812, D2, GRB>(leds, NUM_LEDS); }

void color(int r, int g, int b) {
for (int i = 0; i < NUM_LEDS; i++) {
      leds[i].setRGB(r, g, b);
    }
    FastLED.show();
}

// green = 0, 255, 0
// yellow = 255, 255, 0
// blue = 0, 0, 255
// light blue = 51, 255, 255
// pink = 255, 0, 255
// red = 255, 0, 0

void loop() {

  color(0, 255, 0);
  delay(2000);
  color(255, 255, 0);
  delay(2000);
  color(255, 255, 0);
  delay(2000);
  color(0, 0, 255);
  delay(2000);
  color(255, 0, 0);
  delay(2000);
  color(0, 255, 0);
  delay(2000);

  color(0, 0, 0);
  
  delay(1000);
  color(255, 0, 0);
  delay(2000);
  color(0, 0, 255);
  delay(2000);
  color(51, 255, 255);
  delay(2000);
  color(255, 0, 255);
  delay(2000);
  color(255, 255, 0);
  delay(2000);
  color(0, 0, 255);
  delay(2000);

  color(0,0,0);

  delay(1000);
  color(51, 255, 255);
  delay(2000);
  color(255, 0, 255);
  delay(2000);
  color(0, 255, 0);
  delay(2000);
  color(255, 0, 0);
  delay(2000);
  color(255, 0, 255);
  delay(2000);
  color(51, 255, 255);
  delay(2000);

  color(0,0,0);

  delay(5000);
}