import { Component, OnInit } from '@angular/core';
import {FormGroup, FormBuilder, Validators, Form} from "@angular/forms";
import {ApiService} from "../services/api.service";
import {MatDialogRef} from "@angular/material/dialog";

@Component({
  selector: 'app-dialog',
  templateUrl: './dialog.component.html',
  styleUrls: ['./dialog.component.scss']
})
export class DialogComponent implements OnInit {

  productForm !: FormGroup;
  constructor(private formBuilder : FormBuilder, private api :ApiService, private dialogRef : MatDialogRef<DialogComponent>) {}

  ngOnInit(): void {
    this.productForm = this.formBuilder.group({
      productName : ['', Validators.required],
      date : ['', Validators.required],
      price : ['', Validators.required],
    })
  }

  addProduct() {
    if(this.productForm.valid) {
      this.api.postProduct(this.productForm.value)
        .subscribe({
          next:(res)=>{
            alert("Product added succesfully")
            this.productForm.reset()
            this.dialogRef.close()
          },
          error:()=>{
            alert("Error while adding the product")
          }
        })
    }
  }

}
