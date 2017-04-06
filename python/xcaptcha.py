from antigate import AntiGate, AntiCaptcha, AntiGateError
#from captcha2upload import CaptchaUpload
from lib.controller.Captcha2Upload import CaptchaUpload
from lib.controller.ImageTyperz import ImageTyperz
import time
import urllib
import random
from lib.model.xdata import XData
import sys
import os
from threading import Thread
from lib.controller.xtimeout import TimeOutDriver
from threading import Thread
import timeit
from PIL import Image
#import timeout_decorator

class CaptchaSolve:

    # c1b6bcdd7b6b9311880cb24fb03c357d
    # mine = 41c88de3c01420780ded8fd46e3ed6ef

    def __init__(self, pmaster, captcha_api, captcha_key=None, captcha_user=None, captcha_pass=None):
        #self.log = log
        self.pmaster = pmaster
        self.stop_flag = False

        d = XData()

        self.captcha_api_service = captcha_api
        self.service = None

        if self.captcha_api_service == "2captcha":
            self.api_key = captcha_key

        elif self.captcha_api_service == "imagetyperz":
            self.api_user = captcha_user
            self.api_pass = captcha_pass

        elif self.captcha_api_service == "antigate":
            self.api_key = captcha_key

        self.max_try = d.get_max_retry_elements()
        self.sleep_delay = d.get_retry_elements_delay()
        self.credit_used = 0

    # clicks on I'm not robot
    def find_widget(self, driver, limit_log=False):

        found_widget = False
        found_widget_tries = 0

        while not found_widget:
            if found_widget_tries > self.max_try:
                return False
            try:

                # click on "im not robot"
                driver.switch_to.default_content()
                driver.switch_to_frame(driver.find_element_by_xpath('//iframe[@title="recaptcha widget"]'))
                driver.find_element_by_id("recaptcha-anchor").click()
                if not limit_log:
                    self.pmaster.log("Clicked on widget")
                found_widget = True
                return True
            except Exception as e:
                print "Exception clicking captcha widget = "+str(e)

                found_widget_tries += 1

                try:
                    driver.switch_to_frame(driver.find_element_by_xpath('//iframe[@title="widget del recaptcha"]'))
                    driver.find_element_by_id("recaptcha-anchor").click()
                    found_widget = True
                    return True
                except Exception as e:
                    print "Exception 2 clicking captcha widget = "+str(e)

                    if not limit_log:
                        self.pmaster.log("trying to find captcha widget")

                if not limit_log:
                    self.pmaster.log("trying to find captcha widget")
                print "trying to find captcha widget"
                time.sleep(self.sleep_delay)

        return found_widget

    # finds the popup
    def find_popup(self, driver, limit_log=False):

        found_popup = False
        found_popup_tries = 0

        while not found_popup:
            if found_popup_tries > self.max_try:
                return False
            try:
                # context inside popup
                driver.switch_to.default_content()
                driver.switch_to_frame(driver.find_element_by_xpath('//*[@title="recaptcha challenge"]'))
                found_popup = True
            except:
                found_popup_tries += 1
                if not limit_log:
                    self.pmaster.log("trying to find popup")
                print "trying to find popup"
                time.sleep(self.sleep_delay)

        return found_popup

    def solve(self, driver, limit_log=False):
        self.captcha_type = "recaptcha"  # could also be "text"
        done = False

        """
        Find captcha widget
        """
        found_widget = self.find_widget(driver, limit_log)
        if not found_widget:
            print "didnt find widget"
            return False

        """
        Find captcha popup
        """
        self.find_popup(driver, limit_log)

        """ STRESS TESTING
        test_input = raw_input("continue?")
        if not "y" in test_input:
            return False
        """


        if not limit_log:
            self.pmaster.log("doing challenge")
        done_challenge = False
        done_challenge_tries = 0

        while not done_challenge:
            print "inside while"
            if done_challenge_tries > self.max_try:
                return False
            try:

                """
                Get string challenge, table type and captcha image
                """
                self.challenge_str = driver.find_element_by_class_name("rc-imageselect-desc-no-canonical").text
                if not limit_log:
                    self.pmaster.log("challenge is '" + self.challenge_str + "'")

                """
                Gets table object and defines self.type
                """
                self.table = self.get_captcha_table(driver)
                #if self.type == "4x4":
                    #if not limit_log:
                        #self.pmaster.log("captcha is 4x4, exiting and will start again (if retry is > 1)")
                    #return False

                """
                Gets full image to send to 2captcha
                """
                self.image_src = self.get_captcha_image(driver)

                done_challenge = True
            except:
                done_challenge_tries += 1

                # try if captcha is only text response
                try:
                    self.image_src = self.get_captcha_image(driver,type="text")
                    self.captcha_type = "text"

                    self.captcha_text_answer = driver.find_element_by_id('default-response')
                    done_challenge = True
                except:
                    done_challenge_tries += 1
                    print "error: getting challenge image and string"
                    if not limit_log:
                        self.pmaster.log("error: getting challenge image and string")
                    done_challenge = False

        """
        Save image to /tmp/ folder
        """
        try:
            # save image
            resource = urllib.urlopen(self.image_src)
            self.dir_captcha_image = "tmp/captcha_"+str(random.randint(10000, 99999))+".jpg"
            output = open(self.dir_captcha_image,"wb")
            output.write(resource.read())
            output.close()

            if self.type == "4x4":
                outfile = self.dir_captcha_image
                size = (600, 600)
                try:
                    im = Image.open(outfile)
                    new_img = im.resize(size)
                    new_img.save(outfile, "JPEG")
                except IOError:
                    print "cannot create 600px img for '%s'" % outfile
                except Exception as e:
                    print "exception creating 600px img for 4x4 catpchas: "+str(e)

        except Exception as e:
            print "Exception ocurred while saving image: "+str(e)
            return False


        """
        Captchas API's access
        """
        #if self.type != "4x4":
        if True:

            try:

                """
                2Captcha API
                """
                if self.captcha_api_service == "2captcha":

                    captcha2upload = CaptchaUpload(self.api_key)
                    self.service = captcha2upload
                    #self.log.warn("2captcha balance = "+captcha2upload.getbalance())

                    self.captcha_result = captcha2upload.solve(self.dir_captcha_image, text=self.challenge_str, type=self.type)
                    print "data returned 2captcha = "+str(self.captcha_result)
                    self.credit_used += 1

                    # couldnt solve
                    if self.captcha_result == 1:
                        self.pmaster.log("Captcha service couldn't find a solution")
                        self.report_bad_answer()
                        return False
                    else:
                        captcha_answer = True

                """
                Antigate API
                """
                if self.captcha_api_service == "antigate":

                    antigate = AntiGate(str(self.api_key))
                    self.service = antigate
                    #self.log.warn("antigate balance = "+str(antigate.balance()))

                    captcha_id = antigate.send(self.dir_captcha_image)
                    self.captcha_result = antigate.get(captcha_id)
                    print "data returned antigate = "+str(self.captcha_result)
                    self.credit_used += 1

                    captcha_answer = True

                    self.clean_captcha_img()

                """
                ImageTyperz API
                """
                if self.captcha_api_service == "imagetyperz":

                    imagetyperz = ImageTyperz(self.api_user,self.api_pass)
                    self.service = imagetyperz
                    #self.log.warn("imagetyperz balance = "+imagetyperz.get_balance())

                    self.captcha_result = imagetyperz.solve(self.dir_captcha_image)
                    print "data returned by imagetyperz = "+self.captcha_result
                    self.credit_used += 1

                    captcha_answer = True

                    self.clean_captcha_img()

            except AntiGateError:
                print "could not send captcha to Antigate"
                self.pmaster.log("could not send captcha to Antigate")

            except Exception as e:
                print "could not send captcha to"+self.captcha_api_service+"api = "+str(e)
                self.pmaster.log("could not send captcha to "+self.captcha_api_service+" api = "+str(e))

        else:
            print "not trying 4x4 types...goodbye"
            if not limit_log:
                self.pmaster.log("4x4 captchas not supported")
            return False

        #if self.type != "4x4" and captcha_answer:
        if captcha_answer:
            if not limit_log:
                self.pmaster.log("answering captcha")
            ans = self.answer_captcha(driver, self.captcha_result)
            if ans:
                done = True

        return done

    def clean_captcha_img(self):
        try:
            #os.remove(self.dir_captcha_image)
            print ""
        except Exception as e:
            print "couldnt delete captcha image: "+str(e)

    # deprecated for now, needs revision!!
    def check_for_another_challenge(self, driver):
        flag = False

        driver.switch_to.default_content()
        driver.switch_to_frame(driver.find_element_by_xpath('//*[@title="recaptcha challenge"]'))

        first = driver.find_element_by_xpath('//*[@class="rc-imageselect-incorrect-response"]').get_attribute('style')
        second = driver.find_element_by_xpath('//*[@class="rc-imageselect-error-select-more"]').get_attribute('style')
        third = driver.find_element_by_xpath('//*[@class="rc-imageselect-error-dynamic-more"]').get_attribute('style')

        if first != "display:none":
            flag = True
        elif second != "display:none":
            flag = True
        elif third != "display:none":
            flag = True

        return flag

    def answer_captcha(self,driver,captcha_result):

        if self.captcha_type == "text":
            # catpcha text challenge
            self.captcha_text_answer.send_keys(captcha_result)
        else:
            # recaptcha challenge
            driver.switch_to.default_content()
            driver.switch_to_frame(driver.find_element_by_xpath('//*[@title="recaptcha challenge"]'))

            matrix = []

            if self.type == "3x3":
                # 3x3
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[3]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[3]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[3]/div'))

            elif self.type == "4x2":
                # 4x2
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[2]/div'))
                #matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[3]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[2]/div'))
                #matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[3]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[2]/div'))
                #matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[3]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[2]/div'))
                #matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[3]/div'))

            elif self.type == "4x4":
                # 4x4
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[3]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[1]/td[4]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[3]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[2]/td[4]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[3]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[3]/td[4]/div'))

                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[1]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[2]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[3]/div'))
                matrix.append(driver.find_element_by_xpath('//*[@class="rc-imageselect-challenge"]/div[1]/table/tbody/tr[4]/td[4]/div'))

            if 'click' in captcha_result:
                #click:2/3
                tmp = captcha_result.split(":")
                nums = tmp[1].split("/")
            elif '/' in captcha_result:
                nums = captcha_result.split("/")
            elif '\\' in captcha_result:
                nums = captcha_result.split("\\")
            elif ',' in captcha_result:
                nums = captcha_result.split(",")
            else:
                nums = list(captcha_result)

            for x in nums:
                if x:
                    try:
                        index = int(x)-1
                        print "clickin "+str(x)
                        matrix[index].click()
                        time.sleep(0.3)
                    except Exception as e:
                        print "bad answer from "+self.captcha_api_service+", will send refund claim: "+str(e)
                        self.report_bad_answer()
                        return False

        #click on verify
        driver.find_element_by_id('recaptcha-verify-button').click()

        return True

    def report_bad_answer(self):
        if self.captcha_api_service == "imagetyperz":
            self.service.bad_image()
        if self.captcha_api_service == "antigate":
            self.service.abuse()
        if self.captcha_api_service == "2captcha":
            self.service.report_bad()
        return 1

    def save_iframe(self, iframe):
        file = open("iframe.html","w")
        file.write(iframe)
        file.close()

    def save2file(self, filename,txt):
        file = open(filename,"w")
        file.write(txt)
        file.close()

    def save_html(self, driver):
        file = open("page_notsaving.html", "w")
        file.write(driver.execute_script("return document.getElementsByTagName('html')[0].innerHTML").encode('utf-8'))
        file.close()

    # defines table type and table object
    def get_captcha_image(self, driver, type="recaptcha"):

        if type == "recaptcha":
            image = driver.find_element_by_xpath("//div[@id='rc-imageselect-target']/table/tbody/tr[1]/td[1]/div/div[1]/img")
        else:
            image = driver.find_element_by_xpath('*[class="rc-defaultchallenge-payload"]/img')

        image_src = image.get_attribute("src")
        return image_src

    def get_captcha_table(self,driver):
        try:
            table = driver.find_element_by_class_name("rc-imageselect-table-33")
            self.type = "3x3"
            print "table is 3x3"
        except:
            pass

        try:
            table = driver.find_element_by_class_name("rc-imageselect-table-42")
            self.type = "4x2"
            print "table is 4x2"
        except:
            pass

        try:
            table = driver.find_element_by_class_name("rc-imageselect-table-44")
            self.type = "4x4"
            print "table is 4x4"
        except:
            pass

        return table



