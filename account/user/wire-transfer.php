<?php
$pageName = "Wire Transaction";
include_once("layouts/header.php");
require_once("./userPinfunction.php");


?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
                <div class="card component-card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if ($acct_stat === 'active') {
                                    ?>
                                        <h3 class="text-center">Wire Transfer</h3>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Amount</label>
                                                        <div class="input-group ">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 256 256">
                                                                        <g transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                                                            <path d="M34.801 78.271c5.587-7.722 6.832-17.481 7.039-27.414h17.315v-11.73H41.498c-0.257-12.869 2.023-21.502 6.713-25.123 4.03-3.113 10.752-3.061 19.976 0.163l3.868-11.072C58.854-1.519 48.419-0.971 41.044 4.719c-7.872 6.077-11.587 17.374-11.284 34.408H17.136v11.73h12.963c-0.342 14.225-2.923 22.356-13.2 27.019V90h56.203V78.271H34.801z" fill="grey" />
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <input type="number" class="form-control" name="amount" value="<?= $_POST['amount'] ?>" placeholder="Amount" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Beneficiary Account Name</label>
                                                        <div class="input-group ">
                                                            <input type="text" class="form-control" name="acct_name" placeholder="Beneficiary Account Name" aria-label="notification" aria-describedby="basic-addon1" value="<?= $_POST['acct_name'] ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Bank Name</label>
                                                        <div class="input-group ">
                                                            <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?= $_POST['bank_name'] ?>" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Beneficiary Account No</label>
                                                        <div class="input-group ">
                                                            <input type="number" class="form-control" name="acct_number" placeholder="Beneficiary Account Name" aria-label="notification" aria-describedby="basic-addon1" value="<?= $_POST['acct_number'] ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Select Country</label>
                                                        <div class="input-group">
                                                            <select name="acct_country" class='selectpicker' data-width='100%' required>
                                                                <option>Select Country</option>
                                                                <option value="Afganistan">Afghanistan</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bonaire">Bonaire</option>
                                                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                                <option value="Brunei">Brunei</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Canary Islands">Canary Islands</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Channel Islands">Channel Islands</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos Island">Cocos Island</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote DIvoire">Cote DIvoire</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Curaco">Curacao</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic</option>
                                                                <option value="East Timor">East Timor</option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands">Falkland Islands</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="French Southern Ter">French Southern Ter</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Great Britain">Great Britain</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Hawaii">Hawaii</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="India">India</option>
                                                                <option value="Iran">Iran</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea North">Korea North</option>
                                                                <option value="Korea Sout">Korea South</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Laos">Laos</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macau">Macau</option>
                                                                <option value="Macedonia">Macedonia</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Midway Islands">Midway Islands</option>
                                                                <option value="Moldova">Moldova</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Myanmar">Myanmar</option>
                                                                <option value="Nambia">Nambia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherland Antilles">Netherland Antilles</option>
                                                                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                                <option value="Nevis">Nevis</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau Island">Palau Island</option>
                                                                <option value="Palestine">Palestine</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Phillipines">Philippines</option>
                                                                <option value="Pitcairn Island">Pitcairn Island</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                                <option value="Republic of Serbia">Republic of Serbia</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="St Barthelemy">St Barthelemy</option>
                                                                <option value="St Eustatius">St Eustatius</option>
                                                                <option value="St Helena">St Helena</option>
                                                                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                                <option value="St Lucia">St Lucia</option>
                                                                <option value="St Maarten">St Maarten</option>
                                                                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                                <option value="Saipan">Saipan</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="Samoa American">Samoa American</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syria">Syria</option>
                                                                <option value="Tahiti">Tahiti</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania">Tanzania</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Erimates">United Arab Emirates</option>
                                                                <option value="United States of America">United States of America</option>
                                                                <option value="Uraguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Vatican City State">Vatican City State</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                                <option value="Wake Island">Wake Island</option>
                                                                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zaire">Zaire</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>

                                                            </select>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Swift Code</label>
                                                        <div class="input-group ">
                                                            <input type="text" class="form-control" name="acct_swift" placeholder="Swift Code" value="<?= $_POST['acct_swift'] ?>" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Routing Number</label>
                                                        <div class="input-group ">
                                                            <input type="number" class="form-control" name="acct_routing" value="<?= $_POST['acct_routing'] ?>" placeholder="Routing Number" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Select Account Type</label>
                                                        <div class="input-group">
                                                            <select name="acct_type" class='selectpicker' data-width='100%' required>
                                                                <option value="">Select Account Type</option>
                                                                <option value="Savings">Savings Account</option>
                                                                <option value="Current">Current Account</option>
                                                                <option value="Checking">Checking Account</option>
                                                                <option value="Fixed Deposit">Fixed Deposit</option>
                                                                <option value="Non Resident">Non Resident Account</option>
                                                                <option value="Online Banking">Online Banking</option>
                                                                <option value="Domicilary Account">Domicilary Account</option>
                                                                <option value="Joint Account">Joint Account</option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Naration/Purpose</label>
                                                        <div class="input-group ">
                                                            <textarea class="form-control mb-4" rows="3" id="textarea-copy" placeholder="Fund Description" name="acct_remarks" style="resize: none"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <button class="btn btn-primary mb-2 mr-2" name="wire_transfer"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                            <polyline points="16 17 21 12 16 7"></polyline>
                                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                                        </svg> Transfer</button>
                                                </div>
                                            </div>
                                        </form>
                                </div>
                            <?php
                                    } else {
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">

                                            <div class="media">
                                                <div class="alert-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg>
                                                </div>
                                                <div class="media-body">
                                                    <div class="alert-text">
                                                        <strong>Warning! </strong><span> Account on <span class="text-uppercase "><b>hold</b></span> contact support.</span>
                                                    </div>
                                                    <div class="alert-btn">
                                                        <a class="btn btn-default btn-dismiss" href="mailto:<?= $url_email ?>">Contact Us</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                                    }
                        ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php
        include_once("layouts/footer.php");
        ?>