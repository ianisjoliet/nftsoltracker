import {Component, Inject, OnInit, Optional} from '@angular/core';
import {ApiService} from "../service/api.service";
import {MatDialogRef, MAT_DIALOG_DATA} from "@angular/material/dialog";
import {FormBuilder, FormControl, FormGroup, Validators} from "@angular/forms";

@Component({
  selector: 'app-calcul',
  templateUrl: './calcul.component.html',
  styleUrls: ['./calcul.component.scss']
})
export class CalculComponent implements OnInit {

    calculForm !: FormGroup;
  result: number = 0
  constructor(private formBuilder : FormBuilder,
              private api :ApiService,
              @Optional() public dialogRef: MatDialogRef<CalculComponent>,
              @Optional() @Inject(MAT_DIALOG_DATA) public data: any) {}

  ngOnInit(): void {
    this.calculForm = this.formBuilder.group({
      price : ['', Validators.required],
      fees : ['', Validators.required],
      buySell : ['buy', Validators.required],
    })

    if (this.data) {
      this.calculForm.controls['price'].setValue(this.data.price);
      this.calculForm.controls['fees'].setValue(this.data.fees);
      this.calculForm.controls['buySell'].setValue(this.data.buySell);
    }
  }

  calculFloor() {

    if (this.calculForm.valid) {
      this.api.calculFloorLimit(this.calculForm.value)
        .subscribe(data => {
          this.result = data;
        })
    }
  }


}
