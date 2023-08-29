<template>
    <div>
        <ErrorPopUp :alert="alert" v-if="alert"/>
        <div :class="`calBody ${vibrate ? 'vibrate' : ''}`">
            <CalculatorScreenComponent :value="currentValue" />

            <div class="calRow">
                <ButtonComponent value="C" :callback="clearAllValues" additional-css-selector="clear" />
                <ButtonComponent value="⌫" :callback="del" additional-css-selector="del" />
                <ButtonComponent value="+/-" :callback="InverseSign" additional-css-selector="reverse-sign"/>
                <ButtonComponent value="/" :callback="appendOperation" additional-css-selector="op" />
            </div>

            <div class="calRow">
                <ButtonComponent :key="i" v-for="i in ['1', '2', '3']" :value="i" :callback="appendValue" />
                <ButtonComponent value="x" :callback="appendOperation" additional-css-selector="op" />
            </div>

            <div class="calRow">
                <ButtonComponent :key="i" v-for="i in ['4', '5', '6']" :value="i" :callback="appendValue" />
                <ButtonComponent value="-" :callback="appendOperation" additional-css-selector="op" />
            </div>

            <div class="calRow">
                <ButtonComponent :key="i" v-for="i in ['7', '8', '9']" :value="i" :callback="appendValue" />
                <ButtonComponent value="+" :callback="appendOperation" additional-css-selector="op" />
            </div>

            <div class="calRow">
                <ButtonComponent value="0" :callback="appendValue" additional-css-selector="w-50" />
                <ButtonComponent value="." :callback="appendValue" />
                <ButtonComponent value="=" :callback="calculate" additional-css-selector="op" />
            </div>
        </div>
    </div>
</template>

<script>
import ButtonComponent from "./components/ButtonComponent.vue";
import CalculatorScreenComponent from "./components/CalculatorScreenComponent.vue";
import ErrorPopUp from "./components/ErrorPopUp.vue";

export default {
    name: 'Calculator',
    components: {ErrorPopUp, CalculatorScreenComponent, ButtonComponent},
    data() {
        return {
            currentValue: '0',
            previousValue: null,
            currentOperation: null,
            vibrate: false,
            alert: '',
        };
    },
    methods: {
        // resets all the component values
        clearAllValues() {
            this.currentValue = '0';
            this.previousValue = null;
            this.currentOperation = null;
            this.vibrate = false;
            this.alert = '';
        },
        // Removes the latest character of currentValue
        del() {
            if (this.currentValue.length > 1) {
                this.currentValue = this.currentValue.slice(0, -1);
            } else {
                this.currentValue = '0';
            }
        },
        // Toggles number's positive/negative sign for currentValue.
        InverseSign() {
            if (this.currentValue !== '0') {
                if (this.currentValue.startsWith('-')) {
                    this.currentValue = this.currentValue.substring(1);
                } else {
                    this.currentValue = '-' + this.currentValue;
                }
            }
        },
        // appends the pressed value to currentValue
        appendValue(value) {
            if([null, ''].includes(this.currentValue) && value === '.'){
                this.currentValue = '0.';
            }
            if(value === '.' && this.currentValue.includes('.')){
                return;
            }
            if ([null, '0'].includes(this.currentValue) && value !== '.') {
                this.currentValue = value;
            } else {
                this.currentValue += value;
            }
        },
        // affect the pressed operand to currentOperation
        appendOperation(operation) {
            if (!this.previousValue) {
                this.previousValue = this.currentValue;
                this.currentValue = '';
            }
            this.currentOperation = operation;
        },
        // shows the error pop up and vibrate the calculator
        popUpError(message = 'Oops, Something went wrong. Please try again later'){
            this.alert = message;
            this.vibrate = true;
            setTimeout(() => {
                this.alert = '';
            }, 2000);
            setTimeout(() => {
                this.vibrate = false;
            }, 1000);
        },
        // Async API call, handles the calculus see CalculatorController@calculate
        async calculate() {
            if (this.previousValue && this.currentOperation) {
                const formData = new FormData();
                formData.append('previousValue', this.previousValue);
                formData.append('currentValue', this.currentValue);
                formData.append('operation', this.currentOperation);

                try {
                    const response = await axios.post('/api/calculate', formData);

                    if (response.data.success) {
                        this.currentValue = response.data.result.toString();
                        this.previousValue = null;
                        this.currentOperation = null;
                    } else if (response.data.error){
                        this.popUpError(response.data.error);
                        this.clearAllValues();
                    }else{
                        this.popUpError();
                    }
                } catch (error) {
                    this.popUpError();
                    this.clearAllValues();
                }
            }
        }
    }
}
</script>
