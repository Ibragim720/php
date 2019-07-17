/*
 * main.c
 * 
 * Copyright 2018 ibragim <ibragim@ibragim-Latitude-5490>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */


#include <stdio.h>
#include <math.h>

void run_1(const int n)
{
    int i, j;
    double a = 1.0;
    for (j=0; j<n/100; j++) {
        for (i=0; i<n; i++) {
            a = a + i * 0.5/0.259454 * 1.0*(1000.0);
        }        
    }
    printf("a=%f", a);
}

int main(int argc, char **argv)
{
    printf("hello %d\n", argc);
    printf("hello world and mr Kim\n");
    run_1(10000000);
    return 0;
}

