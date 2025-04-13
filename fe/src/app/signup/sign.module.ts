import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SignupComponent } from './signup.component';
import { FormsModule } from '@angular/forms';  // <-- Tambahkan ini agar [(ngModel)] bisa digunakan

@NgModule({
  declarations: [
    SignupComponent
  ],
  imports: [
    CommonModule,
    FormsModule  // <-- Pastikan ini ada
  ]
})
export class SignupModule { }
