class AuctionsController < ApplicationController
  include ActionView::Helpers::NumberHelper # for number_to_currency
  
  before_filter :get_auction, :only => [:destory, :update, :bid, :edit, :show]
  
  # GET /auctions
  def index
    @auctions = Auction.find(:all, :conditions => "end_time >= "+Time.new.to_i.to_s)

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @auctions }
      format.xml { render xml: @auctions }
    end
  end

  # GET /auctions/1
  def show
    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @auction }
    end
  end

  # GET /auctions/new
  def new
    if 1 == 2 # auth for admin
      render_404
    else
      @auction = Auction.new
  
      respond_to do |format|
        format.html # new.html.erb
        format.json { render json: @auction }
      end
    end
  end

  # GET /auctions/1/edit
  def edit
  end

  # POST /auctions
  def create
    @auction = Auction.new(params[:auction])
    @auction.end_time = Time.new.to_i + 3600*24 # Set end_time 24 hours ahead of now

    respond_to do |format|
      if @auction.save
        format.html { redirect_to @auction, notice: 'Auction was successfully created.' }
        format.json { render json: @auction, status: :created, location: @auction }
      else
        format.html { render action: "new" }
        format.json { render json: @auction.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /auctions/1
  def update

    respond_to do |format|
      if @auction.update_attributes(params[:auction])
        format.html { redirect_to @auction, notice: 'Auction was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @auction.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /auctions/1
  def destroy
    @auction.destroy

    respond_to do |format|
      format.html { redirect_to auctions_url }
      format.json { head :no_content }
    end
  end
  
 # POST /auctions/bid
  def bid
    @auction.price += 1
    @auction.user_id = @current_user.id
    @auction.save()
    
    render :json => { 
      'id' => @auction.id,
      'price' => (number_to_currency @auction.price*0.01),
      'username' => @auction.user.username
    }
  end
  
  # POST /auctions/view_as/list
  def view_as
    session[:view_as] = params[:type]
    render :nothing => true
  end
  
  # DRY Functions
  def get_auction
  	@auction = Auction.find(params[:id])
  end
end
